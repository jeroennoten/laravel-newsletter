import Vue from 'vue';

export default function (listId, addresses) {
    return Promise.all(addresses.map(address => submitAddress(listId, address)));
}

function submitAddress(listId, address) {
    return Vue.http.post(`/admin/newsletter-lists/${listId}/members`, {
        email: address.address,
        name: address.name
    }).then(response => {
        address.status = response.json().status;
    });
}