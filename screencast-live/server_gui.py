import tkinter as tk
from tkinter import messagebox, simpledialog
from server import start_server


def show_clients():
    with lock:
        data = '\n'.join(active_clients)
    messagebox.showinfo('Active Clients', data if data else 'No clients.')

def kick_client():
    ip = simpledialog.askstring('Kick Client', 'Enter client IP to remove:')
    with lock:
        if ip in active_clients:
            active_clients.remove(ip)
            messagebox.showinfo('Client Removed', f'{ip} has been removed.')
        else:
            messagebox.showwarning('Not Found', 'Client IP not connected.')

def start_streaming():
    if not getattr(start_streaming, "started", False):
        start_server()
        start_streaming.started = True
        messagebox.showinfo("Server", "Streaming started.")

root = tk.Tk()
root.title("Tutor Screencaster")
root.geometry("640x240")
root.resizable(False, False)

menu = tk.Menu(root)
file_menu = tk.Menu(menu, tearoff=0)
file_menu.add_command(label="Start Streaming", command=start_streaming)
file_menu.add_command(label="List Active Clients", command=show_clients)
file_menu.add_command(label="Disconnect Client", command=kick_client)
file_menu.add_separator()
file_menu.add_command(label="Exit", command=root.quit)
menu.add_cascade(label="File", menu=file_menu)

help_menu = tk.Menu(menu, tearoff=0)
help_menu.add_command(label="About", command=lambda: messagebox.showinfo("About", "Developed by Furkan Uysal"))
menu.add_cascade(label="Help", menu=help_menu)

root.config(menu=menu)
tk.Label(root, text="Tutor Streaming Application").pack(pady=20)
root.mainloop()
