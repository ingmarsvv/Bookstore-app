<script setup>

import Pagination from '@/Components/Pagination.vue';
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { debounce } from 'lodash';

const titlePhrase = ref('') //holds title search value
const authorPhrase = ref('') //holds author search value
const sortMonth = ref('all') //gets updated from select tag, default is all
const props = defineProps({
    books: Object,
    optionsFilterByMonth: Array, //these are options used by select tag
})
const purchaseSuccessMessage = ref('')
const purchaseErrorMessage = ref('')
const purchaseBookId = ref('');

// function uses titlePhrase, authorPhrase, sortMonth values from input fields
const searchByTitleOrAuthorOrMonth = debounce(() => {
    const params = {}
    if (titlePhrase.value){
        params.titlePhrase = titlePhrase.value
    }
    if (authorPhrase.value){
        params.authorPhrase = authorPhrase.value
    }
    if (sortMonth.value !== 'all'){
        params.year = sortMonth.value.year
        params.month = sortMonth.value.month
    }

    router.get(route('book.index'), params, {
        preserveState: true,
        preserveScroll: true,
    })
}, 500);

//observes changes titlePhrase.value and triggers search function
watch(titlePhrase, () => {
    searchByTitleOrAuthorOrMonth();
});

//observes changes authorPhrase.value and triggers search function
watch(authorPhrase, () => {
    searchByTitleOrAuthorOrMonth();
})

//post request to PurchaseController, function triggered by button "on-click"
const buyBook = (id) => {
    router.post(route('book.purchase', id),{},{
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            purchaseSuccessMessage.value = 'Pirkums veiksmīgs!'
            purchaseBookId.value = id
            setTimeout(() => purchaseSuccessMessage.value = '', 3000)
        },
        onError: () => {
            purchaseErrorMessage.value = 'Pirkums neveiksmīgs!'
            purchaseBookId.value = id
            setTimeout(() => purchaseErrorMessage.value = '', 3000)
        },
    })
}
</script>

<template>
    <div class="2xl:w-2/3 mx-4 my-16 2xl:mx-auto bg-white border-4 border-white rounded-lg">

        <!-- TABLE STARTS HERE -->

        <table class="table-fixed rounded border-collapse w-full ">
            <thead class="bg-amber-400">
                <tr class="text-center">
                    <th class="text-left p-1 text-2xl">
                        Grāmatas nosaukums
                        <div><input type="text" v-model="titlePhrase" class="h-8 w-50 rounded-lg text-sm p-1" placeholder="Meklēt pēc nosaukuma"></div>
                    </th>
                    <th class="p-1 text-2xl">
                        Autori
                        <div><input type="text" v-model="authorPhrase" class="h-8 w-32 rounded-lg text-sm p-1" placeholder="Meklēt pēc autora"></div>
                    </th>
                    <th class="p-1 text-2xl">
                        Pirkumi
                        <div>
                           <select
                                v-model="sortMonth"
                                @change="searchByTitleOrAuthorOrMonth"
                                class="h-8 w-40 rounded-lg text-sm p-1"
                            >
                                <option value="all" >
                                    Pilna vēsture
                                </option>
                                <option v-for="option in props.optionsFilterByMonth" :key="option.value" :value="{ month: option.value[0], year: option.value[1] }">
                                    {{ option.label }}
                                </option>
                            </select> 
                        </div>
                    </th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="bg-gray-100">
                <tr v-for="book in books.data" :key="book.id" class="border-t border-gray-300 text-center">
                    <td class="text-left p-1">{{ book.title }}</td>
                    <td class="p-1">
                        <div v-if="book.authors.length == 0">
                            <p>Grāmatai nav autoru</p>
                        </div>
                        <div v-else v-for="author in book.authors">
                            {{ author.name }}
                        </div>
                    </td>
                    <td>{{ book.purchases_count }}</td>
                    <td>
                        <button v-on:click="buyBook(book.id, index)" class="bg-gray-300 hover:bg-gray-400 rounded-xl p-1">Nopirkt</button>
                        <div v-if="purchaseSuccessMessage && book.id === purchaseBookId" class="text-green-600"> {{ purchaseSuccessMessage }}</div>
                        <div v-if="purchaseErrorMessage && book.id === purchaseBookId" class="text-red-600"> {{ purchaseSuccessMessage }}</div>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Table ends here -->

        <!-- Paginator -->

        <Pagination :links="books.links" />
    </div>
</template>