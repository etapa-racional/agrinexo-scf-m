import subprocess
subprocess.run(["python3", "/opt/app-root/src/engine/AGNSCFMIV.py","-l"],capture_output=False)
subprocess.run(["python3", "/opt/app-root/src/engine/AGNSCFEIV.py","-l"],capture_output=False)
subprocess.run(["python3", "/opt/app-root/src/engine/AGNSCFNIV.py","-l"],capture_output=False)
subprocess.run(["python3", "/opt/app-root/src/engine/AGNSCFAIV.py","-l"],capture_output=False)