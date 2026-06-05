<?php require_once '../app/Views/layout/header.php'; ?>

<main class="w-full max-w-md bg-voice-card border border-voice-green/30 rounded-2xl p-8 md:p-10 shadow-glow mx-auto mt-10 md:mt-16">
    <header class="text-center mb-8">
        <div class="flex justify-center mb-6">
            <h1 class="text-5xl font-black tracking-tighter text-voice-green italic">V.O.I.C.E</h1>
        </div>
        <h2 class="text-2xl font-semibold text-white">Create an Account</h2>
    </header>

    <?php require_once '../app/Core/Flash.php'; Flash::display('auth_error'); ?>

    <form action="<?= URLROOT ?>/auth/register" method="POST" class="space-y-6">
        <div class="relative">
            <input class="w-full py-2 px-0 input-underline text-white placeholder-gray-500 bg-transparent" id="student_num" name="student_num" placeholder="Student Number" required type="text"/>
        </div>

        <div class="relative">
            <input class="w-full py-2 px-0 input-underline text-white placeholder-gray-500 bg-transparent" id="name" name="name" placeholder="Full Name" required type="text"/>
        </div>

        <div class="relative">
            <input class="w-full py-2 px-0 input-underline text-white placeholder-gray-500 bg-transparent" id="email" name="email" placeholder="Email Address (firstname.lastname@olivarezcollege.edu.ph)" pattern="[a-zA-Z]+\.[a-zA-Z]+@olivarezcollege\.edu\.ph" title="Must be firstname.lastname@olivarezcollege.edu.ph" required type="email"/>
        </div>

        <div class="relative">
            <input class="w-full py-2 px-0 input-underline text-white placeholder-gray-500 bg-transparent" id="password" name="password" placeholder="Password" required type="password"/>
        </div>

        <div class="relative">
            <input class="w-full py-2 px-0 input-underline text-white placeholder-gray-500 bg-transparent" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required type="password"/>
        </div>

        <div class="pt-4">
            <button class="w-full bg-voice-green hover:bg-voice-green-dark text-voice-dark font-bold py-3 px-4 rounded-full transition-colors duration-200 text-lg shadow-glow" type="submit">
                Create Account
            </button>
        </div>
        
        <div class="text-center mt-6">
            <p class="text-gray-400 text-sm">
                Already have an account? <a class="text-voice-green hover:underline font-semibold" href="<?= URLROOT ?>/auth/login">Log In</a>
            </p>
        </div>
    </form>
</main>

<?php require_once '../app/Views/layout/footer.php'; ?>