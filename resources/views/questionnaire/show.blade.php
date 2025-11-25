<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $questionnaire->title }}</title>
    <script src="//unpkg.com/alpinejs" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-black text-white">
    <div class="max-w-2xl mx-auto py-8">
        <h2 class="text-2xl font-bold mb-4">{{ $questionnaire->title }}</h2>
        <p class="mb-2">{{ $questionnaire->description }}</p>

        @if (session('status'))
        <div class="bg-{{ session('status') == 'Congrats you have passed' ? 'green' : 'red' }}-600 text-white px-4 py-2 rounded mb-4">
            {{ session('status') }}
        </div>
        @endif

        <form method="POST" action="#">
            @csrf
            @foreach ($questionnaire->Question as $qIndex => $question)
            <div class="mb-6 p-4 bg-gray-900 rounded shadow">
                <label class="block mb-2 font-semibold">
                    Q{{ $loop->iteration }}: {{ $question->text }}
                </label>
                <div>
                    @foreach($question->Answer as $answer)
                    <label class="flex items-center mb-2">
                        <input
                            type="radio"
                            name="responses[{{ $question->id }}]"
                            value="{{ $answer->id }}"
                            class="mr-2">
                        {{ $answer->text }}
                    </label>
                    @endforeach
                </div>
            </div>
            @endforeach
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-800">Submit</button>
        </form>
    </div>
</body>

</html>