<template>
    <div v-if="loading" class="text-center"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></div>
    <div v-else>
        <input class="form-control" style="max-width: 200px;" v-model="search" debounce="300" placeholder="Zoeken">
        <table class="table no-margin table-striped">
            <thead>
            <tr>
                <th>E-mailadres</th>
                <th>Naam</th>
                <th>Ingeschreven</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="member in membersFiltered">
                <td>{{ member.address }}</td>
                <td>{{ member.name }}</td>
                <td>{{ member.subscribed ? 'Ja' : 'Nee' }}</td>
                <td>
                    <delete-button @delete="deleteMember($index)"></delete-button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    import matchSorter from 'match-sorter';

    export default {
        props: ['listId'],
        data() {
            return {
                loading: true,
                members: [],
                search: '',
            }
        },
        ready() {
            this.$http.get(`/admin/newsletter-lists/${this.listId}/members`).then(response => {
                this.members = response.data;
                this.loading = false;
            });
        },
        computed: {
            membersFiltered() {
                return this.search ? matchSorter(this.members, this.search, {keys: ['address', 'name']}) : this.members;
            }
        },
        methods: {
            deleteMember(i) {
                this.$http.delete(`/admin/newsletter-lists/${this.listId}/members/${this.members[i].address}`).then(() => {
                    this.members.splice(i, 1);
                });
            }
        }
    }
</script>
