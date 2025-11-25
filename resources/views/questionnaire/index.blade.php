<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Questionnaires</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-black text-white">
    <div class="max-w-2xl mx-auto py-8">
        <h1 class="text-3xl font-bold mb-6">Active Questionnaires</h1>

        @if($questionnaires->count())
        <ul>
            @foreach($questionnaires as $questionnaire)
            <li class="mb-4 p-4 bg-gray-900 rounded shadow">
                <a
                    href="/questionnaires/{{ $questionnaire->id }}"
                    class="text-indigo-400 hover:underline text-xl font-semibold">{{ $questionnaire->title }}</a>
                <p class="mt-2 text-gray-300">{{ $questionnaire->description }}</p>
            </li>
            @endforeach
        </ul>
        @else
        <p>No active questionnaires available.</p>
        @endif
    </div>
</body>

</html>