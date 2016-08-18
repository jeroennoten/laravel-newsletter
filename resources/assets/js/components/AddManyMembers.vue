<template>
    <div>
        <div v-if="adding">
            <p v-if="canAddMore">Klaar! <button class="btn btn-xs btn-success" @click="addMore">Meer leden toevoegen</button></p>
            <p v-else><strong>Toevoegen van addressen...</strong></p>
            <add-members :addresses="addressesToAdd"></add-members>
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
    import submitAddresses from '../submitAddresses';

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
                    this.addressesToAdd = addresses.map(address => ({
                        address: address.address,
                        name: address.name,
                        status: 'pending'
                    }));
                    this.canAddMore = false;
                    submitAddresses(this.listId, this.addressesToAdd).then(() => {
                        this.canAddMore = true;
                    });
                }
            },
            addMore() {
                this.addressList = '';
                this.adding = false;
            }
        }
    }
</script>