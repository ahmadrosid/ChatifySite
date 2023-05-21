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
                    <p class="max-w-xl mx-auto mt-4 text-base lg:text-xl text-slate-500">
                        List conversations.
                    </p>
                </div>
            </div>
            <div class="max-w-lg mx-auto mt-10">
                <div class="relative flex items-start p-4 space-x-3 bg-white shadow group rounded-2xl">
                    <div class="flex-1 min-w-0">
                        <div>
                            @foreach($chats as $chat)
                            <div class="pb-6">
                                <p class="text-gray-600 text-sm">
                                    {{$chat->embed_collection->created_at->diffForHumans()}}
                                </p>
                                <p class="text-gray-800 font-medium">
                                    <a class="hover:underline" href="{{route('chat.show', $chat->id)}}">
                                        {{$chat->embed_collection->name}}
                                    </a>
                                </p>
                            </div>
                            @endforeach
                            <p id="progress-text" class="text-gray-600"></p>
                        </div>
                        <form class="flex gap-2" id="form-submit-link">
                            @csrf
                            <input placeholder="Paste any link..." class="w-full p-2 rounded-md border border-gray-600 focus:outline-none" name="link" />
                            <button id="btn-submit-indexing" type="submit" class="bg-black text-white shadow px-3 rounded-md inline-flex items-center gap-2">
                                <span>Submit</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

</body>

</html>