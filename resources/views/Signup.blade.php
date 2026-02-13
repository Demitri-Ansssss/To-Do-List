<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])


    <title>SignUp</title>
</head>
<body>
    <div x-data="SignUpForm()" class="flex justify-center items-center w-full h-screen">
        <flux:card class="space-y-6 shadow-2xl border-accent border-2 w-full max-w-sm">
        <div class=" bg-amber-200 text-center p-4 rounded-lg">
            <flux:heading size="lg">SignUp to your account</flux:heading>
        </div>
    
        <div class="space-y-6">
            <template x-if="errorMessage">
                <flux:text variant="danger" class="text-sm font-medium" x-text="errorMessage"></flux:text>
            </template>
            <flux:input x-model="name" label="Name" type="text" placeholder="Your name" />
            <flux:input x-model="email" label="Email" type="email" placeholder="Your email address" />
            <flux:input x-model="password" label="Password" type="password" placeholder="Password" />
            <flux:input x-model="password_confirmation" label="Confirm Password" type="password" placeholder="Confirm Password" />
            
        </div>
    
        <div class="space-y-2">
            <flux:button @click="submitSignup" variant="primary" class="w-full">Register</flux:button>
        </div>
        </flux:card>
    </div>

    <script>
    function SignUpForm() {
        return {
            name:'',
            email: "",
            password:'',
            password_confirmation:'',
            errorMessage: '',
            async submitSignup(){
                try {
                    const response = await fetch('/api/auth/register', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            name: this.name,
                            email: this.email,
                            password: this.password,
                            password_confirmation: this.password_confirmation,
                            device_name: 'browser'
                        })
                    });
                    
                    const data = await response.json();
                    
                    if (response.ok) {
                        localStorage.setItem('token', data.access_token);
                        window.location.href = '/';
                    } else {
                        if (data.errors) {
                            this.errorMessage = Object.values(data.errors).flat().join(' ');
                        } else {
                            this.errorMessage = data.message || 'Email atau password salah.';
                        }
                    }
                
                } catch (error) {
                    console.error('Sign Up error:', error);
                    this.errorMessage = 'Terjadi kesalahan koneksi ke server.';
                }
            }
        }
        
    }
    </script>
</body>

</html>