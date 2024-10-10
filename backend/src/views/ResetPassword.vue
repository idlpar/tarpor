<template>
    <div class="flex items-center justify-center min-h-screen">
        <div class="flex min-w-max max-w-md w-full justify-center bg-gray-900 py-12 px-4 sm:px-6 lg:px-8 m-auto">
            <div class="w-full space-y-8">
                <div class="text-center">
                    <!-- Placeholder for the logo -->
                    <img class="mx-auto h-12 w-auto" src="/logos/tblue.svg" alt="Logo" />
                    <h2 class="mt-6 text-center text-3xl font-extrabold text-white">Set a new password</h2>
                    <p class="mt-2 text-center text-sm text-gray-400">
                        Enter a new password for your account.
                    </p>
                </div>

                <form class="w-full bg-gray-800 p-8 rounded-lg shadow-lg space-y-6" @submit.prevent="submitForm">
                    <div class="rounded-md shadow-sm space-y-1">
                        <div>
                            <label for="password" class="flex items-start text-sm font-medium leading-6 text-gray-300 mb-1">
                                New Password
                            </label>
                            <input
                                id="password"
                                name="password"
                                type="password"
                                v-model="password"
                                autocomplete="new-password"
                                required
                                class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-600 placeholder-gray-500 text-white bg-gray-700 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="New password"
                            />
                        </div>

                        <div>
                            <label for="confirm-password" class="flex items-start text-sm font-medium leading-6 text-gray-300 mb-1">
                                Confirm New Password
                            </label>
                            <input
                                id="confirm-password"
                                name="confirm-password"
                                type="password"
                                v-model="confirmPassword"
                                autocomplete="new-password"
                                required
                                class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-600 placeholder-gray-500 text-white bg-gray-700 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="Confirm new password"
                            />
                        </div>
                    </div>

                    <div>
                        <button
                            type="submit"
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            Set New Password
                        </button>
                    </div>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-400">
                        <router-link to="/login" class="font-medium text-indigo-500 hover:text-indigo-400">
                            Return to sign in
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
            password: '',
            confirmPassword: '',
            token: '', // This will hold the reset token from the URL
        };
    },
    mounted() {
        // Get the token from the URL
        this.token = this.$route.params.token;
        if (!this.token) {
            alert('Invalid reset link. Please try again.');
            this.$router.push('/forgot-password');
        }
    },
    methods: {
        submitForm() {
            if (this.password !== this.confirmPassword) {
                alert("Passwords don't match!");
                return;
            }

            // Simulate form submission with token
            console.log('Token:', this.token);
            console.log('Password:', this.password);

            // You can replace this with an API call for setting the new password.
            // For example:
            // axios.post('/api/reset-password', {
            //   token: this.token,
            //   password: this.password,
            // })

            alert('Your password has been reset successfully!');
            this.$router.push('/login');
        },
    },
};
</script>

<style scoped>
/* Scoped styles for this component */
</style>
