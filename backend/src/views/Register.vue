<template>
    <div class="flex items-center justify-center min-h-screen">
        <div class="flex min-w-max max-w-md w-full justify-center bg-gray-900 py-12 px-4 sm:px-6 lg:px-8 m-auto">
            <div class="w-full space-y-8">
                <div class="text-center">
                    <!-- Placeholder for the logo -->
                    <img class="mx-auto h-12 w-auto" src="/logos/tblue.svg" alt="Logo" />
                    <h2 class="mt-6 text-center text-3xl font-extrabold text-white">Create your account</h2>
                    <p class="mt-2 text-center text-sm text-gray-400">
                        Join us today! Enter your details below to sign up.
                    </p>
                </div>

                <form class="w-full bg-gray-800 p-8 rounded-lg shadow-lg space-y-6" @submit.prevent="submitForm">
                    <div class="rounded-md shadow-sm space-y-1">
                        <div>
                            <label for="name" class="flex items-start text-sm font-medium leading-6 text-gray-300 mb-1">
                                Name
                            </label>
                            <input
                                id="name"
                                name="name"
                                type="text"
                                v-model="name"
                                autocomplete="name"
                                required
                                class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-600 placeholder-gray-500 text-white bg-gray-700 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="Full name"
                            />
                        </div>

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
                                class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-600 placeholder-gray-500 text-white bg-gray-700 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="Email address"
                            />
                        </div>

                        <div>
                            <label for="password" class="flex items-start text-sm font-medium leading-6 text-gray-300 mb-1">
                                Password
                            </label>
                            <input
                                id="password"
                                name="password"
                                type="password"
                                v-model="password"
                                autocomplete="new-password"
                                required
                                class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-600 placeholder-gray-500 text-white bg-gray-700 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="Password"
                            />
                        </div>

                        <div>
                            <label for="confirm-password" class="flex items-start text-sm font-medium leading-6 text-gray-300 mb-1">
                                Confirm Password
                            </label>
                            <input
                                id="confirm-password"
                                name="confirm-password"
                                type="password"
                                v-model="confirmPassword"
                                autocomplete="new-password"
                                required
                                class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-600 placeholder-gray-500 text-white bg-gray-700 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="Confirm password"
                            />
                        </div>
                    </div>

                    <div>
                        <button
                            type="submit"
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            Sign Up
                        </button>
                    </div>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-400">
                        Already have an account?
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
            name: '',
            email: '',
            password: '',
            confirmPassword: ''
        };
    },
    methods: {
        submitForm() {
            // Perform validation to ensure the passwords match
            if (this.password !== this.confirmPassword) {
                alert("Passwords don't match!");
                return;
            }

            // Handle form submission logic
            console.log('Name:', this.name);
            console.log('Email:', this.email);
            console.log('Password:', this.password);

            // You can add your backend API call for registration here
        },
    },
};
</script>

<style scoped>
/* Scoped styles for any custom styling */
</style>
