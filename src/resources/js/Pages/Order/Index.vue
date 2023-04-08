<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import {useForm} from '@inertiajs/vue3';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Head } from '@inertiajs/vue3';


defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    login_id: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Login" />
    <div class="ly_login_container">
        <header class="ly_login_header">
            <div class="navbar navbar-light border-bottom p-1">
                <div class="container-fluid">
                    <div class="d-flex flex-row bd-highlight">
                        <div class="p-1 mx-3 bd-highlight">
                            <img src="/images/food_journal_logo.png" class="navbar-brand-img" alt="alis_logo" width="195" height="31">
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <main class="ly_login_main">
            <div class="bl_login_form">
                <div class="card bg-white mb-4 p-2">
                    <div class="card-title">
                        <div class="bl_login_form_title">Login</div>
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="submit">

                            <div id="js-keydown">
                                <div class="bl_login_form_item">
                                    <InputLabel for="login_id" value="Login ID"/>
                                    <TextInput
                                        id="login_id"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.login_id"
                                        required
                                        autofocus
                                        autocomplete="login_id"
                                    />
                                    <InputError class="mt-2" :message="form.errors.login_id"/>
                                </div>

                                <div class="bl_login_form_item">
                                    <InputLabel for="password" value="Password"/>

                                    <TextInput
                                        id="password"
                                        type="password"
                                        class="mt-1 block w-full"
                                        v-model="form.password"
                                        required
                                        autocomplete="current-password"
                                    />

                                    <InputError class="mt-2" :message="form.errors.password"/>
                                </div>

                            </div>

                            <div class="row pt-2">
                                <div class="col-12 text-center">
                                    <PrimaryButton class="btn-outline-secondary" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                        Log in
                                    </PrimaryButton>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
        <footer class="ly_login_footer">
            <div class="navbar navbar-light border-top">
                <div class="col-12 text-center">
                    Copyright &copy; FoodJournal Co., Ltd.
                </div>
            </div>
        </footer>

    </div>

</template>
