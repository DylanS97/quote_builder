<template>
    <div class="flex-1 rounded-md bg-gray-100 shadow-2xl p-10 pl-0 pt-6">
        <div class="p-2 mb-8 bg-gray-300 pl-10 w-92 relative border-t border-b border-gray-600 overflow-hidden">
            <h2 class="text-2xl font-medium">Add Customer Details</h2>
            <div class="title-tag bg-gray-100 h-10 w-10 top-1 left-88"></div>
        </div>

        <div class="flex justify-between bg-white rounded-md mb-4 ml-10">
            <div class="p-4">
                <div class="field-container w-80 h-14">
                    <input class="field rounded-md" type="text" id="first_name" placeholder=" " v-model="details['client_first_name']">
                    <label for="first_name" class="label bg-white top-4">First Name: <span class="text-red-500">*</span></label>
                </div>
                <span v-if="errors.client_first_name" class="text-red-500">{{ errors.client_first_name[0] }}</span>
            </div>
            <div class="p-4">
                <div class="field-container w-80 h-14">
                    <input class="field rounded-md" type="text" placeholder=" " id="last_name" v-model="details['client_last_name']">
                    <label for="last_name" class="label bg-white top-4">Last Name: <span class="text-red-500">*</span></label>
                </div>
                <span v-if="errors.client_last_name" class="text-red-500">{{ errors.client_last_name[0] }}</span>
            </div>
        </div>
        
        <div class="flex justify-between bg-white rounded-md ml-10">
            <div class="p-4">
                <div class="field-container w-80 h-14">
                    <input class="field rounded-md" type="text" placeholder=" " id="phone" v-model="details['client_phone']">
                    <label for="phone" class="label bg-white top-4">Contact No: <span class="text-red-500">*</span></label>
                </div>
                <span v-if="errors.client_phone" class="text-red-500">{{ errors.client_phone[0] }}</span>
            </div>
            <div class="p-4">
                <div class="field-container w-80 h-14">
                    <input class="field rounded-md" type="text" placeholder=" " id="email" v-model="details['client_email']">
                    <label for="email" class="label bg-white top-4">Email: <span class="text-red-500">*</span></label>
                </div>
                <span v-if="errors.client_email" class="text-red-500">{{ errors.client_email[0] }}</span>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'detailsTab',

    props: ['details', 'errors']
}
</script>