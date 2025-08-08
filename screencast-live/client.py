# client.py

import socket
import cv2
import numpy as np
import os
import time


DEFAULT_IP = "192.168.0.106"  #
DEFAULT_PORT = 9999


save_dir = "screenshots"
frames = []
save_images = False
record_video = False

def run(ip=DEFAULT_IP, port=DEFAULT_PORT, save_images_flag=False, record_flag=False):
    global save_images, record_video
    save_images, record_video = save_images_flag, record_flag

    try:
        port = int(port)
    except Exception:
        print(" Port hatalı:", port)
        return

    if save_images and not os.path.exists(save_dir):
        os.makedirs(save_dir)

    sock = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
    sock.settimeout(5)

    try:
        sock.sendto(b"JOIN", (ip, port + 1))
        print(f" {ip}:{port} için yayın bekleniyor…")
    except Exception as e:
        print(" JOIN gönderme hatası:", e)
        return

    try:
        while True:
            try:
                data, _ = sock.recvfrom(131072)  
            except socket.timeout:
                print("️  Server’dan veri alınamadı (timeout)")
                continue
            except ConnectionResetError:
                print(" Bağlantı resetlendi. Server kapatıldı mı?")
                break

            frame = cv2.imdecode(np.frombuffer(data, np.uint8), cv2.IMREAD_COLOR)

            if frame is not None:
                cv2.imshow("Live Stream", frame)

                if save_images:
                    fname = f"{save_dir}/f_{int(time.time()*1000)}.jpg"
                    cv2.imwrite(fname, frame)

                if record_video:
                    frames.append(frame)

            if cv2.waitKey(1) == ord("q"):
                break

    finally:
        try:
            sock.sendto(b"LEAVE", (ip, port + 1))
        except:
            pass
        sock.close()
        cv2.destroyAllWindows()

        if record_video and frames:
            h, w, _ = frames[0].shape
            out = cv2.VideoWriter("recorded.avi", cv2.VideoWriter_fourcc(*"XVID"), 10, (w, h))
            for fr in frames:
                out.write(fr)
            out.release()
