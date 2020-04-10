from flask import Flask, render_template, request, send_from_directory, redirect, url_for
import os
from flask_cors import CORS
import json
from werkzeug.utils import secure_filename

UPLOAD_FOLDER = 'static/uploads'
ALLOWED_EXTENSIONS = {'png', 'jpg', 'jpeg'}

app = Flask(__name__)
app.config['UPLOAD_FOLDER'] = UPLOAD_FOLDER

CORS(app)

@app.route("/")
def about():
    return render_template("index.html")

@app.route("/result")
def result(filename):
    return render_template("result.html", filename=filename)

def allowed_file(filename):
    return '.' in filename and \
           filename.rsplit('.', 1)[1].lower() in ALLOWED_EXTENSIONS

@app.route('/uploads', methods=['GET', 'POST'])
def upload_file():
    if request.method == 'POST':
        # check if the post request has the file part
        if 'file' not in request.files:
            return redirect("http://localhost:8000")
        file = request.files['file']
        # if user does not select file, browser also
        # submit an empty part without filename
        if file.filename == '':
            return redirect("http://localhost:8000")
        if file and allowed_file(file.filename):
            filename = secure_filename(file.filename)
            file.save(os.path.join(app.config['UPLOAD_FOLDER'], filename))
            return result(filename)
    

@app.route('/uploader/<filename>')
def uploaded_file(filename):
    return send_from_directory(app.config['UPLOAD_FOLDER'],
                               filename)


if __name__ == "__main__":
    app.run(host="0.0.0.0", port=8000, debug=True)