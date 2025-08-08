import tkinter as tk
from tkinter import messagebox
import threading
from client import run

running = False

def connect_to_server():
    global running
    if running:
        messagebox.showinfo("Already Running", "Client is already connected.")
        return

    ip = ip_entry.get().strip()
    port_str = port_entry.get().strip()

    try:
        port = int(port_str)
        if not (1024 <= port <= 65535):
            raise ValueError
    except ValueError:
        messagebox.showerror("Invalid Port", "Port must be between 1024 and 65535.")
        return

    save = save_var.get()
    record = rec_var.get()

    threading.Thread(target=run, args=(ip, port, save, record), daemon=True).start()
    running = True
    messagebox.showinfo("Connecting", f"Connecting to {ip}:{port}")

root = tk.Tk()
root.title("Client Screencast")
root.geometry("300x220")
root.resizable(False, False)

tk.Label(root, text="Server IP:").pack(pady=5)
ip_entry = tk.Entry(root); ip_entry.insert(0, "192.168.1.115"); ip_entry.pack()

tk.Label(root, text="Port:").pack(pady=5)
port_entry = tk.Entry(root); port_entry.insert(0, "9999"); port_entry.pack()

save_var = tk.BooleanVar()
rec_var = tk.BooleanVar()
tk.Checkbutton(root, text="Save Images", variable=save_var).pack()
tk.Checkbutton(root, text="Record Video", variable=rec_var).pack()

tk.Button(root, text="Connect", command=connect_to_server).pack(pady=10)

root.mainloop()
