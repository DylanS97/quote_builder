<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div id="navigation" class="w-60 bg-gray-200 float-left fixed h-screen border-r-4 border-red-500 duration-300">
        <div id="nav-header" class="flex justify-between text-right p-4 border-b-4 border-red-500">
            <router-link to="/" class="w-min">
                <x-application-logo class="block h-10 w-auto fill-current text-gray-600" />
            </router-link>
            <div id="nav-control-cont" class="relative">
                <div id="nav-control-cover" class="hidden h-full bg-gray-200 absolute w-half border-r-4 border-red-500 z-20"></div>
                <i id="nav-control" class="fas fa-arrow-circle-left text-4xl duration-300 mr-1 cursor-pointer"></i>
            </div>
        </div>
        <router-link to="/" id="home" class="block w-full p-4 text-lg text-center">
            <i class="fas fa-home nav-icons hidden text-2xl"></i>
            <span class="nav-text font-normal">Home</span>
        </router-link>
        <div class="border-t-2 border-red-500">
            <div class="nav-dropdown-trigger text-lg cursor-pointer block p-4 text-center">
                <i class="fas fa-file-alt nav-icons hidden text-2xl"></i>
                <span id="quotes" class="nav-text w-full font-normal">Quotes</span>
            </div>
            <div class="nav-dropdown hidden">
                <div class="">
                    <hr class="nav-break border-red-500 w-0 float-right duration-300">
                    <router-link :to="{name: 'quotes'}" id="view-quote" class="block p-4 text-center">
                        <i class="fas fa-eye nav-icons hidden text-xl"></i>
                        <span class="nav-text font-normal">View Quotes</span>
                    </router-link>
                </div>
                <router-link :to="{name: 'quote_create'}" id="create-quote" class="block p-4 text-center">
                    <i class="fas fa-plus-square nav-icons hidden text-xl"></i>
                    <span class="nav-text font-normal">New Quotes</span>
                </router-link>
            </div>
        </div>
        <div class="border-t-2 border-red-500">
            <div class="nav-dropdown-trigger cursor-pointer text-lg block p-4 text-center">
                <i class="fas fa-box-open nav-icons hidden text-2xl"></i>
                <span id="products" class="nav-text w-full font-normal">Products</span>
            </div>
            <div class="nav-dropdown hidden">
                <div class="">
                    <hr class="nav-break border-red-500 w-0 float-right duration-300">
                    <router-link :to="{name: 'products'}" id="view-product" class="block p-4 text-center">
                        <i class="fas fa-eye nav-icons hidden text-xl"></i>
                        <span class="nav-text font-normal">View Products</span>
                    </router-link>
                </div>
                <router-link :to="{name: 'product_create'}" id="create-product" class="block p-4 text-center">
                    <i class="fas fa-plus-square nav-icons hidden text-xl"></i>
                    <span class="nav-text font-normal">Create Product</span>
                </router-link>
            </div>
        </div>
    </div>
</nav>