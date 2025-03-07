<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';

const form = useForm({
    email: '',
});

const submit = async () => {
    try {
        await form.post(route('password.email'));
        showMessage();
    } catch (error) {
        console.error(error);
    }
};

const showMessage = () => {
    alert('We sent a secret key to the account!');
};

const goToSecretKeyInput = () => {
    window.location.href = route('password.secret.view');
};

const { props } = usePage();
const status = props.value?.flash?.status || '';
</script>

<template>
    <GuestLayout>
        <Head title="Forgot Password" />
        <div class="mb-4 text-sm text-gray-600">
            Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" />
                <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Email Password Reset Link
                </PrimaryButton>
            </div>
        </form>

        <div v-if="status" class="mt-4 text-green-600">
            {{ status }}
        </div>

        <div class="mt-4">
            <button @click="goToSecretKeyInput" class="underline text-sm text-gray-600 hover:text-gray-900">
                Enter your secret key
            </button>
        </div>
    </GuestLayout>
</template>

