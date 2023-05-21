<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>ChatifySite</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <!-- <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" /> -->
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

</head>

<body class="antialiased">
    <section class="flex relative bg-[#f5f5f5] items-center justify-center min-h-screen">
        <div class="relative items-center w-full px-5 py-12 mx-auto lg:px-14 lg:py-16 max-w-7xl md:px-12">
            <div>
                <div class="text-center">
                    <p class="w-auto"><a href="/" class="font-semibold text-[#4354ff] text-sm uppercase">ChatifySite</a></p>
                    @if($source == "landing_page")
                    <p class="mt-8 text-3xl font-extrabold tracking-tight text-black md:text-5xl">
                        Turn Websites into Intelligent<br />Chatbot Conversations
                    </p>
                    <p class="max-w-xl mx-auto mt-4 text-base lg:text-xl text-slate-500">
                        Transform static pages into dynamic conversations, engage users, and deliver information with a conversational touch.
                    </p>
                    @endif
                </div>
            </div>
            <div class="max-w-lg mx-auto mt-10">
                @if($source == "chatbot")
                <div class="p-2 text-center pb-6">
                    <p><span class="text-gray-800 font-medium">{{$embed_collection->name}}</span></p>
                    <p><a href="{{$embed_collection->meta_data->url}}" class="text-blue-500">{{$embed_collection->meta_data->url}}</a></p>
                </div>
                @endif
                <div class="relative flex items-start p-4 space-x-3 bg-white shadow group rounded-2xl">
                    <div class="flex-1 min-w-0">
                        @if($source == "chatbot")
                        <div class="pb-10 space-y-4 h-[60vh] overflow-scroll" id="messages">
                            @foreach($messages as $message)
                            @if($message->role == "user")
                            <div class="ml-16 flex justify-end">
                                <di class="bg-gray-100 p-3 rounded-md">
                                    <p class="font-medium text-blue-500 text-right text-sm">Question</p>
                                    <hr class="my-2" />
                                    <p class="text-gray-800">{{$message->content}}</p>
                                </di>
                            </div>
                            @else
                            <div class="bg-gray-100 p-2 rounded-md mr-16">
                                <p class="font-medium text-blue-500 text-sm">Answer</p>
                                <hr class="my-2" />
                                <p class="text-gray-800">{{$message->content}}</p>
                            </div>
                            @endif
                            @endforeach
                        </div>
                        <form class="flex gap-2 pt-2" id="form-question">
                            @csrf
                            <input type="hidden" name="_chat_id" value="{{$chat->id}}" />
                            <input placeholder="Ask any question!" class="w-full p-2 rounded-md border border-gray-600 focus:outline-none" name="question" />
                            <button id="btn-submit-question" type="submit" class="bg-black text-white shadow px-3 rounded-md flex items-center">Send</button>
                        </form>
                        @endif

                        @if($source == "landing_page")
                        <p id="progress-text" class="text-gray-500"></p>
                        <form class="flex gap-2" id="form-submit-link">
                            @csrf
                            <input placeholder="Paste any link..." class="w-full p-2 rounded-md border border-gray-600 focus:outline-none" name="link" />
                            <button id="btn-submit-indexing" type="submit" class="bg-black text-white shadow px-3 rounded-md inline-flex items-center gap-2">
                                <span>Submit</span>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

</body>

</html>