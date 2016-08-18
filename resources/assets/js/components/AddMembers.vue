<template>
    <table class="table">
        <thead>
        <tr>
            <th>E-mailadres</th>
            <th>Naam</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="address in addresses">
            <td>{{ address.address }}</td>
            <td>{{ address.name }}</td>
            <td>
                <span v-show="address.status == 'pending'">
                    <i class="fa fa-spinner fa-pulse fa-fw"></i>
                </span>
                <span v-show="address.status == 'ok'" class="text-success">
                    <i class="fa fa-check fa-fw"></i>
                </span>
                <span v-show="address.status == 'invalid'" class="text-danger">
                    <i class="fa fa-times fa-fw"></i>
                </span>
            </td>
        </tr>
        </tbody>
    </table>
</template>

<script>
    export default {
        props: ['addresses']
    }
</script>