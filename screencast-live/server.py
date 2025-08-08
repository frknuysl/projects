# server.py  –  Çalışan, optimize edilmiş sürüm
import socket, threading, time
import pyautogui, cv2, numpy as np

PORT            = 9999
HANDSHAKE_PORT  = PORT + 1        
FPS             = 10              
MAX_UDP_SIZE    = 65507           

clients = set()
lock    = threading.Lock()

# ----------------------------- EKRAN YAKALAMA -----------------------------
def capture_screen() -> bytes:
    """Ekranı yakala, küçült, JPEG'e çevir ve byte dizisi döndür."""
    screenshot = pyautogui.screenshot()
    frame = cv2.cvtColor(np.array(screenshot), cv2.COLOR_RGB2BGR)

    
    frame = cv2.resize(frame, (0, 0), fx=0.4, fy=0.4)

    
    ok, buffer = cv2.imencode('.jpg', frame,
                              [int(cv2.IMWRITE_JPEG_QUALITY), 30])
    return buffer.tobytes() if ok else b''

# ----------------------------- HANDSHAKE -----------------------------
def start_handshake_listener() -> None:
    """JOIN/LEAVE mesajlarını dinle (10000/UDP)."""
    def listen():
        with socket.socket(socket.AF_INET, socket.SOCK_DGRAM) as s:
            s.bind(('', HANDSHAKE_PORT))
            print(" Handshake listener başlatıldı...")

            while True:
                try:
                    data, addr = s.recvfrom(1024)
                    if data == b"JOIN":
                        with lock:
                            clients.add(addr)
                        print(f" Client joined {addr[0]}")
                    elif data == b"LEAVE":
                        with lock:
                            clients.discard(addr)
                        print(f" Client left {addr[0]}")
                except Exception as e:
                    print("  Handshake hatası:", e)

    threading.Thread(target=listen, daemon=True).start()

# ----------------------------- ANA YAYIN -----------------------------
def start_server() -> None:
    start_handshake_listener()
    with lock:   
        with socket.socket(socket.AF_INET, socket.SOCK_DGRAM) as s:
            print(" Yayın başladı...")
            while True:
                frame = capture_screen()
                if len(frame) == 0:
                    continue                    

                if len(frame) > MAX_UDP_SIZE:   
                    print(f" Frame atlandı ({len(frame)} bayt > {MAX_UDP_SIZE})")
                    continue

                with lock:
                    for client in list(clients):
                        try:
                            s.sendto(frame, client)
                            print(f" {len(frame)} bayt → {client[0]}")
                        except Exception as e:
                            print(f" Gönderim hatası → {client[0]}: {e}")

                time.sleep(1 / FPS)


if __name__ == "__main__":
    start_server()
