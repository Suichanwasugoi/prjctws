<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    email: '',
    secret_key: '',
});

const submit = () => {
    form.post(route('password.secret'));
    showMessage();
};

const showMessage = () => {
    alert('Your Password has been reset to "1234"');
};

const goToLogin = () => {
    window.location.href = route('login');
};
</script>

<template>
    <GuestLayout>
        <Head title="Enter Secret Key" />
        <div class="mb-4 text-sm text-gray-600">
            Please enter the email address and secret key sent to your email to reset your password.
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" />
                <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>
            <div class="mt-4">
                <InputLabel for="secret_key" value="Secret Key" />
                <TextInput id="secret_key" type="text" class="mt-1 block w-full" v-model="form.secret_key" required />
                <InputError class="mt-2" :message="form.errors.secret_key" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Submit Secret Key
                </PrimaryButton>
            </div>
        </form>

        <div class="mt-4">
            <button @click="goToLogin" class="underline text-sm text-gray-600 hover:text-gray-900">
                Back to Login Page
            </button>
        </div>
    </GuestLayout>
</template>
