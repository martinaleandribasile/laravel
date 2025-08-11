<template>
    <div class="welcome-container">
        <div class="welcome-logo">
            <span class="logo-w">W</span><span class="logo-e">ea</span><span class="logo-r">r</span><span class="logo-h">h</span><span class="logo-o">ou</span><span class="logo-s">s</span><span class="logo-e2">e</span>
        </div>
        <Link href="/login" class="welcome-login-btn">Login</Link>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}
</script>


<style scoped>
.welcome-container {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: #f7f8fa;
}
.welcome-logo {
    font-family: 'Montserrat', 'Segoe UI', Arial, sans-serif;
    font-size: 3rem;
    font-weight: 900;
    letter-spacing: 2px;
    margin-bottom: 2.5rem;
}
.logo-w { color: #4fd1c5; }
.logo-e { color: #222e3c; }
.logo-r { color: #fbbf24; }
.logo-h { color: #222e3c; }
.logo-o { color: #4fd1c5; }
.logo-s { color: #fbbf24; }
.logo-e2 { color: #222e3c; }
.welcome-login-btn {
    background: #4fd1c5;
    color: #fff;
    font-size: 1.3rem;
    font-weight: 600;
    padding: 0.8rem 2.5rem;
    border-radius: 8px;
    text-decoration: none;
    transition: background 0.2s;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}
.welcome-login-btn:hover {
    background: #38b2ac;
}
</style>

