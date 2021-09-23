<template>
    <div class="absolute top-0 left-0 h-screen w-screen z-50">
        <div class="fixed top-0 left-0 h-screen w-screen bg-gradient-shadow"></div>
        <div class="center-screen flex flex-col justify-between p-6 rounded-md text-center">
            <h2 class="text-2xl mt-4 font-medium">Are You Sure?</h2>
            <div class="flex justify-around">
                <button @click="hideDeleteModal" class="px-5 py-2 rounded-md bg-red-500">
                    <i class="fas fa-times text-white text-4xl"></i>
                </button>
                <button @click="deleteData(data.id)" class="px-4 py-2 rounded-md bg-green-500">
                    <i class="fas fa-check text-white text-4xl"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'delete',

    props: ['deleteData', 'data', 'hideDeleteModal']
}
</script>