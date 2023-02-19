from flask import Flask, request
from flask_restful import Api, Resource, reqparse
from flask_mysqldb import MySQL
from hashlib import sha1
import random as rand
import time
from datetime import datetime


app = Flask(__name__)
api = Api(app)

# Configure MySQL connection
app.config["MYSQL_HOST"] = "localhost"
app.config["MYSQL_USER"] = "root"
app.config["MYSQL_PASSWORD"] = "Faiz1234"
app.config["MYSQL_DB"] = "systemteknik"

# Initialize MySQL extension
mysql = MySQL(app)
# todo find some better way to connet to website
# todo excute inderect instead of directly using f-string
# todo dubble check status codes
def authorize(auth_token: str, cur):
    sql = "select email from users where `token` LIKE '" + auth_token + "'"

    cur.execute(sql)
    result = cur.fetchall()
    if len(result) != 1:
        request.abort("Not allowed", 400)


class Account(Resource):
    def put(self) -> tuple[str, int]:
        conn = mysql.connect
        cur = conn.cursor()
        data = request.form.to_dict()
        if not any(
            [key in data.keys() for key in ["first_name", "last_name", "password", "email"]]
        ):
            request.abort(404, "missing, data")

        data["password"] = str(sha1(str(data["password"]).encode()).hexdigest())
        columns = "`" + "`,`".join(data.keys()) + "`"
        values = "'" + "','".join(data.values()) + "'"
        sql = f"insert into Users ({columns},`token`) values ({values},'{sha1(str(str(data['email']) + 'SALT' + str(time.monotonic()) + str(data['password'])).encode()).hexdigest()}')"
        cur.execute(sql)
        conn.commit()
        conn.close()
        return "Success", 201

    def post(self) -> tuple[str, int]:
        conn = mysql.connect
        cur = conn.cursor()
        token = request.form.to_dict().get("token")
        sql = "update Users set token = %s where token = %s"
        random_token = sha1(str(rand.random() + time.monotonic()).encode()).hexdigest()
        cur.execute(sql, (random_token, token))
        conn.commit()
        conn.close()
        return "Success", 200

    def get(self) -> tuple[tuple[str, str], int]:
        conn = mysql.connect
        cur = conn.cursor()
        sql = "select token from Users where email like %s and password like %s"
        email = request.args.get("email")
        password = request.args.get("password")
        print(email, password)
        cur.execute(sql, (email, sha1(password.encode()).hexdigest()))
        token = cur.fetchall()[0][0]
        conn.close()
        return ("success", token), 200

    def delete(self) -> tuple[str, int]:
        conn = mysql.connect
        cur = conn.cursor()
        token = request.form.get("token")
        sql = "delete from Users where token LIKE %s"
        cur.execute(sql, (token,))
        conn.commit()
        conn.close()
        return "success", 200


class Event(Resource):
    def put(self) -> tuple[tuple[str, list], int]:
        conn = mysql.connect
        cur = conn.cursor()
        data = request.form.to_dict()

        required_parameter = [
            "auth_token",
            "event_name",
            "track_id",
            "host_organization",
            "sport",
            "start_date",
            "end_date",
            "module_id",
        ]

        if not any([key in data.keys() for key in required_parameter]):
            request.abort(404, "missing, data")
        auth_token = data.pop("auth_token")
        authorize(auth_token, cur)
        result = cur.fetchall()

        columns = "`" + "`,`".join(data.keys()) + "`"
        values = "','".join(data.values())

        sql = (
            f"insert into competition ({columns},`host_email`) values ('{values}','{result[0][0]}')"
        )
        cur.execute(sql)
        conn.commit()
        conn.close()
        return "success", 201

    # todo make the return better format
    def get(self) -> tuple[tuple[str], int]:
        conn = mysql.connect
        cur = conn.cursor()
        data = request.args

        sql = f"SELECT * FROM competition WHERE `{data.get('key')}` LIKE '%{data.get('search_text')}%'"
        cur.execute(sql)
        result = cur.fetchall()
        conn.close()
        return (("success", str(result)), 200)

    # todo make so that the person athorize is has the same host_email
    def delete(self) -> tuple[str, int]:
        conn = mysql.connect
        cur = conn.cursor()
        data = request.form
        authorize(data.get("token"), cur)
        sql = "DELETE from competition where `event_id` LIKE %s"

        cur.execute(sql, (data.get("event_id"),))
        conn.commit()
        conn.close()
        return "success", 200


class Result(Resource):
    # todo athorization needed from person and not from machine
    # todo get event id from machine somehow
    # todo check so that the participants exist
    def put(self) -> tuple[str, int]:
        conn = mysql.connect
        cur = conn.cursor()
        data = request.form.to_dict()

        if (
            "track_time" not in data.keys()
            or "participant1" not in data.keys()
            or "event_id" not in data.keys()
        ):
            request.abort("Not enough info", 400)

        columns = "`" + "`,`".join(data.keys()) + "`"
        values = "'" + "','".join(data.values()) + "'"

        now = datetime.today().strftime("%Y-%m-%d %H:%M:%S")
        sql = f"insert into result ({columns},`current_time`) values({values},'{now}')"
        print(sql)
        cur.execute(sql)

        conn.commit()
        conn.close()
        return "Success", 201


api.add_resource(Account, "/account")
api.add_resource(Event, "/event")
api.add_resource(Result, "/result")
if __name__ == "__main__":
    app.run(debug=True)
