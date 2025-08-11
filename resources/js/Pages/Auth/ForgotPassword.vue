<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>


<template>
    <div class="wearhouse-login-bg">
        <div class="wearhouse-login-box">
            <div class="wearhouse-logo">
                <span class="logo-w">W</span><span class="logo-e">ea</span><span class="logo-r">r</span><span class="logo-h">h</span><span class="logo-o">ou</span><span class="logo-s">s</span><span class="logo-e2">e</span>
            </div>
            <h2 class="login-title">Recupera la password</h2>
            <div class="mb-4 text-sm text-gray-600 text-center">
                Hai dimenticato la password? Inserisci la tua email e riceverai il link per reimpostarla.
            </div>
            <div v-if="status" class="mb-4 text-sm font-medium text-green-600 text-center">
                {{ status }}
            </div>
            <form @submit.prevent="submit" class="login-form">
                <div class="form-group">
                    <InputLabel for="email" value="Email" />
                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="username"
                    />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>
                <div class="form-group form-actions">
                    <PrimaryButton
                        class="login-btn"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Invia link di reset
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </div>
</template>

<style scoped>
.wearhouse-login-bg {
    min-height: 100vh;
    background: #f7f8fa;
    display: flex;
    align-items: center;
    justify-content: center;
}
.wearhouse-login-box {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.08);
    padding: 2.5rem 2.5rem 2rem 2.5rem;
    min-width: 340px;
    max-width: 95vw;
    display: flex;
    flex-direction: column;
    align-items: center;
}
.wearhouse-logo {
    font-family: 'Montserrat', 'Segoe UI', Arial, sans-serif;
    font-size: 2.5rem;
    font-weight: 900;
    letter-spacing: 2px;
    margin-bottom: 1.5rem;
}
.logo-w { color: #4fd1c5; }
.logo-e { color: #222e3c; }
.logo-r { color: #fbbf24; }
.logo-h { color: #222e3c; }
.logo-o { color: #4fd1c5; }
.logo-s { color: #fbbf24; }
.logo-e2 { color: #222e3c; }
.login-title {
    font-size: 1.3rem;
    font-weight: 600;
    color: #222e3c;
    margin-bottom: 1.5rem;
    text-align: center;
}
.login-form {
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: 1.2rem;
}
.form-group {
    width: 100%;
}
.form-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    margin-top: 0.5rem;
}
.login-btn {
    background: #4fd1c5;
    color: #fff;
    font-weight: 600;
    border-radius: 8px;
    padding: 0.7rem 2.2rem;
    font-size: 1.1rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    border: none;
    transition: background 0.2s;
}
.login-btn:hover {
    background: #38b2ac;
}
</style>
