<template>
    <div class="flex items-center justify-center min-h-screen">
        <div class="flex min-w-max max-w-md w-full justify-center bg-gray-900 py-12 px-4 sm:px-6 lg:px-8 m-auto">
            <div class="w-full space-y-8">
                <div class="text-center">
                    <img class="mx-auto h-12 w-auto" src="/logos/tblue.svg" alt="Logo" />
                    <h2 class="mt-6 text-center text-3xl font-extrabold text-white">Sign in to your account</h2>
                </div>

                <form class="w-full bg-gray-800 p-8 rounded-lg shadow-lg space-y-6" @submit.prevent="submitForm">
                    <div class="rounded-md shadow-sm space-y-1">
                        <div>
                            <label for="email-address" class="flex items-start text-sm font-medium leading-6 text-gray-300 mb-1">Email address</label>
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
                            <label for="password" class="flex items-start text-sm font-medium leading-6 text-gray-300 mb-1">Password</label>
                            <input
                                id="password"
                                name="password"
                                type="password"
                                v-model="password"
                                autocomplete="current-password"
                                required
                                class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-600 placeholder-gray-500 text-white bg-gray-700 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="Password"
                            />
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input
                                id="remember-me"
                                name="remember-me"
                                type="checkbox"
                                v-model="rememberMe"
                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-600 rounded bg-gray-800"
                            />
                            <label for="remember-me" class="ml-2 block text-sm text-gray-300">Remember me</label>
                        </div>

                        <div class="text-sm">
                            <a href="/forgot-password" class="font-medium text-indigo-500 hover:text-indigo-400">Forgot your password?</a>
                        </div>
                    </div>

                    <div>
                        <button
                            type="submit"
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            Sign in
                        </button>
                    </div>

                    <!-- "Or continue with" section -->
                    <div class="flex items-center justify-center my-6">
                        <div class="flex-grow border-t border-gray-600"></div>
                        <span class="px-4 text-gray-400 text-sm">Or continue with</span>
                        <div class="flex-grow border-t border-gray-600"></div>
                    </div>

                    <div class="flex justify-center space-x-4">
                        <button
                            type="button"
                            class="group relative flex justify-center py-2 px-4 border border-gray-600 text-sm font-medium rounded-md text-gray-300 bg-gray-700 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            <img class="h-5 w-5 mr-2" src="/logos/google.svg" alt="Google" />
                            Google
                        </button>
                        <button
                            type="button"
                            class="group relative flex justify-center py-2 px-4 border border-gray-600 text-sm font-medium rounded-md text-gray-300 bg-gray-700 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            <img class="h-5 w-5 mr-2" src="/logos/github.svg" alt="GitHub" />
                            GitHub
                        </button>
                    </div>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-400">Not a member? <a href="/register" class="font-medium text-indigo-500 hover:text-indigo-400">Register Now!</a></p>
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
            password: '',
            rememberMe: false
        };
    },
    methods: {
        submitForm() {
            console.log('Email:', this.email);
            console.log('Password:', this.password);
            console.log('Remember Me:', this.rememberMe);
        }
    }
};
</script>

<style scoped>
/* You can add custom styles here if needed */
</style>
