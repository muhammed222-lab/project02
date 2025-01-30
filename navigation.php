<!-- Save this as components/navigation.php -->
<nav class="fixed top-0 left-0 right-0 z-50 bg-background/80 backdrop-blur-lg border-b border-[#00ADB5]/10">
    <div class="container mx-auto px-4">
        <div class="flex h-16 items-center justify-between">
            <a href="index.php" class="flex items-center space-x-2">
                <img src="favicon.png" alt="Project02 Logo" class="h-8 w-auto">
                <span class="text-xl font-bold text-[#00ADB5]">Project02</span>
            </a>

            <div class="hidden md:flex items-center space-x-4">
                <a href="#about" class="text-foreground/80 hover:text-[#00ADB5] transition-colors">
                    About Us
                </a>
                <a href="#whyus" class="text-foreground/80 hover:text-[#00ADB5] transition-colors">
                    Why Us
                </a>
                <a href="#services" class="text-foreground/80 hover:text-[#00ADB5] transition-colors">
                    Our Service
                </a>
                <a href="#testimonials" class="text-foreground/80 hover:text-[#00ADB5] transition-colors">
                    Our users
                </a>
                <a href="browse.php" class="hover:text-[#00ADB5] text-[#00ADB5] transition-colors">
                    Browse Projects
                </a>
                <a href="login.php" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-[#00ADB5] text-[#00ADB5] hover:bg-[#00ADB5]/10 h-10 px-4 py-2">
                    Sign In
                </a>
            </div>

            <!-- Mobile Menu Button -->
            <button data-mobile-menu class="md:hidden text-foreground focus:outline-none">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div data-mobile-menu-content class="hidden md:hidden flex flex-col space-y-4 mt-4">
            <a href="#about" class="text-foreground/80 hover:text-[#00ADB5] transition-colors">
                About Us
            </a>
            <a href="#whyus" class="text-foreground/80 hover:text-[#00ADB5] transition-colors">
                Why Us
            </a>
            <a href="#services" class="text-foreground/80 hover:text-[#00ADB5] transition-colors">
                Our Service
            </a>
            <a href="#testimonials" class="text-foreground/80 hover:text-[#00ADB5] transition-colors">
                Our users
            </a>
            <a href="browse.php" class="text-foreground/80 hover:text-[#00ADB5] transition-colors">
                Browse Projects
            </a>
            <a href="login.php" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-[#00ADB5] text-[#00ADB5] hover:bg-[#00ADB5]/10 h-10 px-4 py-2">
                Sign In
            </a>
        </div>
    </div>
</nav>