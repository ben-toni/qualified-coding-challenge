<template>

    <Head>
        <title></title>
    </Head>

    <div class="min-h-full flex flex-col">
        <Header :menu="menu" />

        <main class="xl:py-16 py-8 px-4 sm:px-6 xl:px-8">
            <div class="mx-auto max-w-7xl">
                <slot />
            </div>
        </main>

        <Footer class="mt-auto" />
    </div>

    <Notice />
</template>

<script setup>
    import { computed, defineAsyncComponent } from "vue";
    import { usePage } from '@inertiajs/vue3'

    const Header = defineAsyncComponent(() => import("@js/Components/Header.vue"));
    const Footer = defineAsyncComponent(() => import("@js/Components/Footer.vue"));

    const page = usePage();

    const menu = computed(() => {
        if (page.props.auth.loggedIn) {
            return [
                {
                    label: "Dashboard",
                    route: route("home"),
                    condition: true,
                    components: ['Dashboard/Index'],
                },
                {
                    label: "Items",
                    route: route('items.index'),
                    condition: true,
                    components: ['Items/Index'],
                },
                {
                    label: "Account",
                    route: route("account.edit"),
                    condition: true,
                    components: ['Account/Edit', 'EmailVerification/Show'],
                },
                {
                    label: "Logout",
                    route: route("logout"),
                    method: "post",
                    condition: true,
                    components: [],
                },
            ];
        }

        return [
            {
                label: "Login",
                route: route("login"),
                condition: true,
                components: ['Login/Show'],
            },
            {
                label: "Register",
                route: route("register"),
                condition: true,
                components: ['Register/Show'],
            },
        ];
    });
</script>
