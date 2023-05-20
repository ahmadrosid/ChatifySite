<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <!-- <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" /> -->
    @vite('resources/css/app.css')

</head>

<body class="antialiased">
    <section class="flex relative bg-[#f5f5f5] items-center justify-center min-h-screen">
        <div class="relative items-center w-full px-5 py-12 mx-auto lg:px-16 lg:py-32 max-w-7xl md:px-12">
            <div>
                <div class="text-center">
                    <span class="w-auto"><span class="font-semibold text-[#4354ff] text-sm uppercase">ChatifySite</span></span>
                    <p class="mt-8 text-3xl font-extrabold tracking-tight text-black md:text-5xl">
                        Turn Websites into Intelligent<br />Chatbot Conversations
                    </p>
                    <p class="max-w-xl mx-auto mt-4 text-base lg:text-xl text-slate-500">
                        Transform static pages into dynamic conversations, engage users, and deliver information with a conversational touch.
                    </p>
                </div>
            </div>
            <div class="max-w-lg mx-auto mt-14">
                @if($source == "chatbot")
                <div class="p-2">
                    <p>Chat with: <span class="text-gray-500">https://laravel.com/docs/10.x/queues</span></p>
                </div>
                @endif
                <div class="relative flex items-start p-4 space-x-3 bg-white shadow group rounded-2xl">
                    <div class="flex-1 min-w-0">
                        @if($source == "chatbot")
                        <div class="pb-10 space-y-4 h-[60vh]">
                            <div class="ml-16 flex justify-end">
                                <di class="bg-gray-100 p-3 rounded-md">
                                    <p class="font-medium text-gray-900 text-right text-sm">Question</p>
                                    <hr class="my-2" />
                                    <p class="text-gray-800">What is Laravel queue?</p>
                                </di>
                            </div>
                            <div class="bg-gray-100 p-2 rounded-md mr-16">
                                <p class="font-medium text-gray-900 text-sm">Answer</p>
                                <hr class="my-2" />
                                <p class="text-gray-800">
                                    Laravel queue is a feature of the Laravel framework, which allows you to defer time-consuming or resource-intensive tasks for background processing. It provides a way to handle tasks asynchronously and improve the performance of your application by offloading work to queues.
                                </p>
                            </div>
                        </div>
                        <form class="flex gap-2">
                            <input placeholder="Ask any question!" class="w-full p-2 rounded-md border border-gray-600 focus:outline-none" name="link" />
                            <button type="submit" class="bg-black text-white shadow px-3 rounded-md">Send</button>
                        </form>
                        @endif

                        @if($source == "landing_page")
                        <form class="flex gap-2">
                            <input placeholder="Paste any link..." class="w-full p-2 rounded-md border border-gray-600 focus:outline-none" name="link" />
                            <button type="submit" class="bg-black text-white shadow px-3 rounded-md">Submit</button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

</body>

</html>