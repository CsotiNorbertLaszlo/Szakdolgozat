<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Add Balance') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Add balance to your account.') }}
        </p>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Current Balance: $') }} {{ Auth::user()->balance }}
        </p>
    </header>

    <form method="post" action="{{ route('add.balance') }}" class="mt-6 space-y-6">
        @csrf

        <div>
            <x-input-label for="add_balance_amount" :value="__('Amount')" />
            <x-text-input id="add_balance_amount" name="amount" type="number" class="mt-1 block w-full" placeholder="Enter amount" />
            <x-input-error :messages="$errors->addBalance->get('amount')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Add Balance') }}</x-primary-button>

            @if (session('success') === 'balance-added')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Balance added successfully!') }}</p>
            @endif
        </div>
    </form>
</section>
