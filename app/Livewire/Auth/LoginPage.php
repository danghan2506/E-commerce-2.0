<?php

namespace App\Livewire\Auth;

use Illuminate\Contracts\Session\Session;
use Livewire\Attributes\Title;
use Livewire\Component;
#[Title('Login')]
class LoginPage extends Component
{
    public $email;
    public $password;
    public function save(){
        $this->validate([
            'email' => 'required|email|max:255|exists:users,email',
            'password' => 'required|min:6|max:255',
        ]);
        // Nếu thông tin đăng nhập không hợp lệ (sai email hoặc mật khẩu), 
        // đoạn mã sẽ lưu thông báo lỗi "Invalid credentials" vào session và dừng lại mà không tiếp tục xử lý thêm. 
        if(!auth()->attempt(['email'=> $this->email, 'password' => $this->password])) {
            session()->flash('error', 'Invalid credentials');
            return;
        }
        return redirect()->intended();
    }


    public function render()
    {
        return view('livewire.auth.login-page');
    }
}
