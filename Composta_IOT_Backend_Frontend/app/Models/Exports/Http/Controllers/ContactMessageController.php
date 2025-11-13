<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactMessageController extends Controller
{
    // Mostrar mensajes activos
    public function index()
    {
        $messages = ContactMessage::where('state', '>', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.contacts.gestionContactMessages', compact('messages'));
    }

    // Formulario de edición
    public function edit($id)
    {
        $message = ContactMessage::findOrFail($id);
        return view('admin.contacts.edit', compact('message'));
    }

    // Actualizar mensaje
    public function update(Request $request, $id)
    {
        $message = ContactMessage::findOrFail($id);

        $request->validate([
            'state' => 'required|in:0,1', // 0 = recepcionado, 1 = pendiente
        ]);

        $message->state = $request->state;
        $message->updated_by = Auth::id();
        $message->save();

        return redirect()->route('contacts.edit', $message->id)
            ->with('success', '✅ Mensaje actualizado correctamente.');
    }

    // Mostrar mensajes "eliminados" lógicamente
    public function delete()
    {
        $messages = ContactMessage::where('state', 0)->paginate(10);
        return view('admin.contacts.delete', compact('messages'));
    }

    // Restaurar mensaje
    public function activate($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->state = 1;
        $message->save();

        return redirect()->route('contacts.delete')->with('success', '✅ Mensaje reactivado.');
    }
}
