from flask import Flask, request, jsonify, render_template
from flask_sqlalchemy import SQLAlchemy
from flask_cors import CORS
from os import environ
import json

app = Flask(__name__)

# app.config['SQLALCHEMY_DATABASE_URI'] = 'mysql+mysqlconnector://flight_admin:6kKVm7C2PHtVtgGT@esd-g7t6-rds.cs2kfkrucphj.ap-southeast-1.rds.amazonaws.com:3306/flight_name'
app.config['SQLALCHEMY_DATABASE_URI'] = 'mysql+mysqlconnector://flight_admin:6kKVm7C2PHtVtgGT@esd-g7t6.cakxlnvku8py.ap-southeast-1.rds.amazonaws.com:3306/flight_name'
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False
app.config['SQLALCHEMY_ENGINE_OPTIONS'] = {'pool_size': 10,'pool_recycle': 1800}

db = SQLAlchemy(app)
CORS(app)


class Flight(db.Model):
    __tablename__ = 'flight'

    flightNo = db.Column(db.String(8), primary_key=True)
    departDest = db.Column(db.String(5), nullable=False)
    arrivalDest = db.Column(db.String(5), nullable=False)
    deptTime = db.Column(db.DateTime, nullable=False)
    arrivalTime = db.Column(db.DateTime, nullable=False)
    basePrice = db.Column(db.String(30), nullable=False)
    # type = db.Column(db.String(1), nullable=False)

    def __init__(self, flightNo, departDest, arrivalDest, deptTime, arrivalTime, basePrice):
        self.flightNo = flightNo
        self.departDest = departDest
        self.arrivalDest = arrivalDest
        self.deptTime = deptTime
        self.arrivalTime = arrivalTime
        self.basePrice = basePrice
        # self.type = type

    def json(self):
        return {
            "flightNo": self.flightNo,
            "departDest": self.departDest,
            "arrivalDest": self.arrivalDest,
            "deptTime": str(self.deptTime),
            "arrivalTime": str(self.arrivalTime),
            "basePrice": self.basePrice,
            # "type" : self.type
        }


class Code(db.Model):
    __tablename__ = 'code'

    code = db.Column(db.String(3), primary_key=True)
    name = db.Column(db.String(45))  # , nullable=False

    def __init__(self, code, name):
        self.code = code
        self.name = name

    def json(self):
        return {
            "code": self.code,
            "name": self.name
        }


@app.route("/flight")
def get_all():
    return {"flight": [flight.json() for flight in Flight.query.all()]}


@app.route("/getFlightNo")
def get_all_flight_no():
    # return {"all_flight_no": ["MH123", "MH124"]}
    return {"all_flight_no": [flightNo[0] for flightNo in Flight.query.with_entities(Flight.flightNo).all()]}


@app.route("/flight/<string:departDest>/<string:arrivalDest>")
def get_flight_by_dept_arr(departDest, arrivalDest):
    flights = Flight.query.filter_by(
        departDest=departDest, arrivalDest=arrivalDest)
    if len(flights.all()) != 0:
        return {"flight": [flight.json() for flight in flights], "status": 200} 
    return jsonify({"message": "Flight not found."}), 404

# get flight details by flight number


@app.route("/flight/<string:flightNo>")
def get_flight_by_flight_no(flightNo):
    flight = Flight.query.filter_by(flightNo=flightNo).first()
    if flight:
        return {"flight": flight.json(), "status": 200}
    return jsonify({"message": "Flight not found"}), 404

# Flight microservice interact with Booking UI
# test script
# {
# 	"departDest": "SIN",
# 	"arrivalDest": "KUL"
# }


@app.route("/flight/receive_flights", methods=['POST'])
def receive_flights():
    details = request.get_json()
    result = get_flight_by_dept_arr(
        details['departDest'], details['arrivalDest'])
    replymessage = json.dumps(result)
    if result['status'] == 200:
        return replymessage, 200
    else:
        return replymessage, 502

# Booking microservice interact with Flight microservice


@app.route("/flight/receive_choice", methods=['POST'])
def receive_choice():
    details = request.get_json()
    result = get_flight_by_flight_no(details['flightNo'])
    replymessage = json.dumps(result)
    print(result)
    if result['status'] == 200:
        return replymessage, 200
    else:
        return replymessage, 502


@app.route("/getFlightCode")
def getAllFlightCode():
    return jsonify([code.json() for code in Code.query.all()]), 200



#input is depart/arrive Dest in the form of "KUL"/"SIN"
@app.route("/getcodename/<string:code>")
def getdestname(dest):
    code = Code.query.filter_by(code=dest).first()
    if code:
        return{"name":code.name, "status":200}
    return jsonify({"message": "Couldn't find the country name by code"}), 404



if __name__ == "__main__":
    app.run(host='0.0.0.0', port=5001, debug=True)
