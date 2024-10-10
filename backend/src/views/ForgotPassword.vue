<template>
    <div class="flex items-center justify-center min-h-screen">
        <div class="flex min-w-max max-w-md w-full justify-center bg-gray-900 py-12 px-4 sm:px-6 lg:px-8 m-auto">
            <div class="w-full space-y-8">
                <div class="text-center">
                    <!-- Placeholder for the logo -->
                    <img class="mx-auto h-12 w-auto" src="/logos/tblue.svg" alt="Logo" />
                    <h2 class="mt-6 text-center text-3xl font-extrabold text-white">Set a new password</h2>
                    <p class="mt-2 text-center text-sm text-gray-400 break-words">
                        No worries! Enter your email below, <br> and we'll send you a reset link.
                    </p>
                </div>

                <form class="w-full bg-gray-800 p-8 rounded-lg shadow-lg space-y-6" @submit.prevent="submitForm">
                    <div class="rounded-md shadow-sm space-y-1">
                        <div>
                            <label for="email-address" class="flex items-start text-sm font-medium leading-6 text-gray-300 mb-1">
                                Email address
                            </label>
                            <input
                                id="email-address"
                                name="email"
                                type="email"
                                v-model="email"
                                autocomplete="email"
                                required
                                class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-600 placeholder-gray-500 text-white bg-gray-700 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="Email address"
                            />
                        </div>
                    </div>

                    <div>
                        <button
                            type="submit"
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            Send Reset Link
                        </button>
                    </div>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-400">
                        Remembered your password?
                        <router-link to="/login" class="font-medium text-indigo-500 hover:text-indigo-400">
                            Sign in here!
                        </router-link>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            email: '',
        };
    },
    methods: {
        submitForm() {
            // Handle form submission logic
            console.log('Email:', this.email);
            // You can add API integration for password reset here
        },
    },
};
</script>

<style scoped>
/* You can add custom styles here if needed */
</style>
