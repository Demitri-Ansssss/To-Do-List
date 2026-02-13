<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Login</title>
</head>
<body>
    <div x-data="loginForm()" class="flex justify-center items-center w-full h-screen">
        <flux:card class="space-y-6 shadow-2xl border-accent border-2 w-full max-w-sm">
        <div class=" bg-amber-200 text-center p-4 rounded-lg">
            <flux:heading size="lg">Log in to your account</flux:heading>
        </div>
    
        <div class="space-y-6">
            <template x-if="errorMessage">
                <flux:text variant="danger" class="text-sm font-medium" x-text="errorMessage"></flux:text>
            </template>

            <flux:input x-model="email" label="Email" type="email" placeholder="Your email address" />
    
            <flux:field>
                <div class="mb-3 flex justify-between">
                    <flux:label>Password</flux:label>
    
                    <flux:link href="#" variant="subtle" class="text-sm">Forgot password?</flux:link>
                </div>
    
                <flux:input x-model="password" type="password" placeholder="Your password" />
    
                <flux:error name="password" />
            </flux:field>
        </div>
    
        <div class="space-y-2">
            <flux:button @click="submitLogin" variant="primary" class="w-full">Log in</flux:button>
    
            <flux:button href="/signup" variant="ghost" class="w-full">Sign up for a new account</flux:button>
        </div>
        </flux:card>
    </div>

    <script>
    function loginForm() {
        return {
            email: '',
            password: '',
            errorMessage: '',
            async submitLogin() {
                this.errorMessage = '';
                try {
                    const response = await fetch('/api/auth/login', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            email: this.email,
                            password: this.password,
                            device_name: 'browser'
                        })
                    });
                    
                    const data = await response.json();
                    
                    if (response.ok) {
                        localStorage.setItem('token', data.access_token);
                        window.location.href = '/home';
                    } else {
                        if (data.errors) {
                            this.errorMessage = Object.values(data.errors).flat().join(' ');
                        } else {
                            this.errorMessage = data.message || 'Email atau password salah.';
                        }
                    }
                } catch (error) {
                    console.error('Login error:', error);
                    this.errorMessage = 'Terjadi kesalahan koneksi ke server.';
                }
            }
        }
    }
    </script>
</body>

</html>