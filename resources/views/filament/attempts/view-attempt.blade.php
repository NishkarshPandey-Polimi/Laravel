<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title></title>
    <script src="//unpkg.com/alpinejs" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-black text-white">




    <div class="space-y-6 max-w-3xl">
        <h1 class="text-2xl font-semibold tracking-tight mb-6">
            View Attempt
        </h1>

        {{-- Summary card --}}
        <div class="rounded-xl border border-gray-800 bg-gray-900 px-6 py-4">
            <h2 class="text-lg font-semibold mb-4">Attempt summary</h2>
            <div class="grid md:grid-cols-2 gap-4 text-sm">
                <div>
                    <div class="text-gray-400">Questionnaire</div>
                    <div class="font-medium">{{ $attempt->questionnaire->title ?? '-' }}</div>
                </div>
                <div>
                    <div class="text-gray-400">Name</div>
                    <div class="font-medium">{{ $attempt->name }} {{ $attempt->surname }}</div>
                </div>
                <div>
                    <div class="text-gray-400">Score</div>
                    <div class="font-medium">{{ number_format($attempt->score, 1) }}%</div>
                </div>
                <div>
                    <div class="text-gray-400">Result</div>
                    <div>
                        <span @class([ 'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold' , 'bg-green-600/20 text-green-400 ring-1 ring-green-500/40'=> $attempt->result === 'Passed',
                            'bg-red-600/20 text-red-400 ring-1 ring-red-500/40' => $attempt->result === 'Failed',
                            ])>
                            {{ $attempt->result }}
                        </span>
                    </div>
                </div>
                <div>
                    <div class="text-gray-400">Date</div>
                    <div class="font-medium">
                        {{ $attempt->created_at->format('M d, Y H:i:s') }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Answers list --}}
        <div class="rounded-xl border border-gray-800 bg-gray-900 px-6 py-4">
            <h2 class="text-lg font-semibold mb-4">Answers</h2>

            @if ($attempt->answers->isEmpty())
            <p class="text-sm text-gray-400">No answers recorded for this attempt.</p>
            @else
            <div class="space-y-4">
                @foreach ($attempt->answers as $index => $attemptAnswer)
                <div class="rounded-lg border border-gray-800 bg-gray-950 px-4 py-3">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="text-xs text-gray-500 mb-1">
                                Question {{ $index + 1 }}
                            </div>
                            <div class="font-medium mb-2">
                                {{ $attemptAnswer->question->text ?? '-' }}
                            </div>
                            <div class="text-sm">
                                <span class="text-gray-400">Selected answer:</span>
                                <span class="ml-1">
                                    {{ $attemptAnswer->answer->text ?? '-' }}
                                </span>
                            </div>
                        </div>
                        <div class="mt-1">
                            @if(optional($attemptAnswer->answer)->is_correct)
                            <span class="inline-flex items-center rounded-full bg-green-600/20 px-2.5 py-0.5 text-xs font-semibold text-green-400 ring-1 ring-green-500/40">
                                Correct
                            </span>
                            @else
                            <span class="inline-flex items-center rounded-full bg-red-600/20 px-2.5 py-0.5 text-xs font-semibold text-red-400 ring-1 ring-red-500/40">
                                Wrong
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>


</body>

</html>