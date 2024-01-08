<template>
    <div>
        <group-chat v-for="group in groups" :group="group" :key="group.id"></group-chat>
    </div>
</template>


<script>

export default {
    props: ['user'],
    computed: {
        groups() {
            return this.$store.state.groups;
        }
    },
    mounted() {
        this.listenForNewGroups();
    },
    methods: {
        listenForNewGroups() {
            Echo.private('users.' + this.user.id)
                .listen('GroupCreated', (e) => {
                    this.$store.dispatch('createGroup', e.group);
                });
        }
    }
}
</script>
