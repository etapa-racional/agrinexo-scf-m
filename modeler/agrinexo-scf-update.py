import subprocess
import time

while True:
    time.sleep(10)
    subprocess.run(["python3", "engine/AGNSCFMIV.py"],capture_output=False)
    subprocess.run(["python3", "engine/AGNSCFEIV.py"],capture_output=False)
    subprocess.run(["python3", "engine/AGNSCFNIV.py"],capture_output=False)
    subprocess.run(["python3", "engine/AGNSCFAIV.py"],capture_output=False)