<?php require_once '../app/Views/layout/header.php'; ?>

<main class="w-full max-w-md bg-voice-card border border-voice-green/30 rounded-2xl p-10 shadow-glow mx-auto mt-10 md:mt-20">
    <header class="text-center mb-10">
        <div class="flex items-center justify-center gap-2 mb-2">
            <h1 class="text-4xl font-bold tracking-widest text-voice-green">V.O.I.C.E</h1>
            <svg class="w-8 h-8 text-voice-green" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C11.4477 2 11 2.44772 11 3V21C11 21.5523 11.4477 22 12 22C12.5523 22 13 21.5523 13 21V3C13 2.44772 12.5523 2 12 2Z"></path>
                <path d="M7 7C6.44772 7 6 7.44772 6 8V16C6 16.5523 6.44772 17 7 17C7.55228 17 8 16.5523 8 16V8C8 7.44772 7.55228 7 7 7Z"></path>
                <path d="M17 7C16.4477 7 16 7.44772 16 8V16C16 16.5523 16.4477 17 17 17C17.5523 17 18 16.5523 18 16V8C18 7.44772 17.5523 7 17 7Z"></path>
                <path d="M2 11C1.44772 11 1 11.4477 1 12C1 12.5523 1.44772 13 2 13H3C3.55228 13 4 12.5523 4 12C4 11.4477 3.55228 11 3 11H2Z"></path>
                <path d="M21 11C20.4477 11 20 11.4477 20 12C20 12.5523 20.4477 13 21 13H22C22.5523 13 23 12.5523 23 12C23 11.4477 22.5523 11 22 11H21Z"></path>
            </svg>
        </div>
        <p class="text-sm text-gray-400">Olivarez College Insight System</p>
    </header>

    <?php require_once '../app/Core/Flash.php'; Flash::display('auth_error'); Flash::display('auth_success'); ?>

    <form action="<?= URLROOT ?>/auth/login" method="POST" class="space-y-6">
        <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                </svg>
            </div>
            <input class="voice-input block w-full pl-11 pr-4 py-3 bg-voice-dark border border-voice-border rounded-lg text-gray-200 placeholder-gray-500 transition-all duration-200" id="login_id" name="login_id" placeholder="Student ID or Email" required type="text"/>
        </div>

        <div>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                    </svg>
                </div>
                <input class="voice-input block w-full pl-11 pr-4 py-3 bg-voice-dark border border-voice-border rounded-lg text-gray-200 placeholder-gray-500 transition-all duration-200" id="password" name="password" placeholder="Password" required type="password"/>
            </div>
        </div>

        <button class="w-full py-3 px-4 bg-voice-green hover:bg-voice-green-dark text-voice-dark font-bold rounded-full transition-all duration-200 shadow-lg transform active:scale-95" type="submit">
            Login
        </button>

        <div class="text-center pt-4">
            <p class="text-gray-400 text-sm">
                New to V.O.I.C.E? 
                <a class="text-voice-green font-semibold hover:underline decoration-2 underline-offset-4" href="<?= URLROOT ?>/auth/register">Sign Up</a>
            </p>
        </div>
    </form>
</main>

<?php require_once '../app/Views/layout/footer.php'; ?>