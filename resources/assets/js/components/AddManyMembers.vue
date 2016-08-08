<template>
    <div>
        <div v-if="adding">
            <p v-if="canAddMore">Klaar! <button class="btn btn-xs btn-success" @click="addMore">Meer leden toevoegen</button></p>
            <p v-else><strong>Toevoegen van addressen...</strong></p>
            <table class="table">
                <thead>
                <tr>
                    <th>E-mailadres</th>
                    <th>Naam</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="address in addressesToAdd">
                    <td>{{ address.email }}</td>
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
        </div>
        <div class="add-form" v-else>
            <p>Voer een door komma's gescheiden lijst met e-mailadressen in, eventueel met namen. Extra spaties zijn
                toegestaan.</p>
            <p>Voorbeelden van geldige invoeren:</p>
            <ul>
                <li><code>jan@gmail.com, piet@hotmail.com, info@example.com</code></li>
                <li><code>Jan Smit &lt;jan@gmail.com&gt;, Piet Jansen&lt;piet@hotmail.com&gt;, "Company Example.com"
                    &lt;info@example.com&gt;</code>
                </li>
                <li><code>jan@gmail.com, Piet &lt;piet@hotmail.com&gt;,info@example.com</code></li>
            </ul>
            <div class="form-group">
            <textarea class="form-control" rows="5" placeholder=""
                      style="font-family: Menlo,Monaco,Consolas,'Courier New',monospace"
                      v-model="addressList"></textarea>
                <div class="alert alert-danger" v-show="invalid">Ongeldige invoer</div>
            </div>
            <button type="button" class="btn btn-success" @click="add">Lijst toevoegen</button>
        </div>
    </div>
</template>

<script>
    import emailAddresses from 'email-addresses';


    export default {
        props: ['listId'],
        data() {
            return {
                addressList: '',
                invalid: false,
                adding: false,
                addressesToAdd: [],
                canAddMore: false,
            };
        },
        methods: {
            add() {
                let addresses = emailAddresses.parseAddressList(this.addressList);
                if (!addresses) {
                    this.invalid = true;
                } else {
                    this.adding = true;
                    this.invalid = false;
                    this.addressesToAdd = [];
                    this.canAddMore = false;
                    Promise.all(addresses.map(this.submitAddress)).then(() => {
                        this.canAddMore = true;
                    });
                }
            },
            submitAddress(address) {
                let addressToAdd = {
                    email: address.address,
                    name: address.name,
                    status: 'pending'
                };
                this.addressesToAdd.push(addressToAdd);
                return this.$http.post(`${this.listId}/members`, {
                    email: address.address,
                    name: address.name
                }).then(response => {
                    addressToAdd.status = response.json().status;
                });
            },
            addMore() {
                this.addressList = '';
                this.adding = false;
            }
        }
    }
</script>