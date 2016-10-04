<template>
    <button type="submit" class="btn btn-danger btn-xs" @click="deleteMember" :disabled="deleting">
        <i class="fa fa-spinner fa-pulse fa-fw" v-if="deleting"></i>
        <i class="fa fa-trash fa-fw" v-else></i>
    </button>
</template>

<script>
    export default {
        data() {
            return {
                deleting: false
            };
        },
        methods: {
            deleteMember() {
                this.deleting = true;
                this.$emit('delete');
            }
        }
    }
</script>
