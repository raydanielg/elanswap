export default function Header() {
  return (
    <header className="sticky top-0 z-50">
      {/* Top bar */}
      <nav className="bg-white/90 backdrop-blur border-b border-gray-200">
        <div className="flex flex-wrap justify-between items-center mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 h-16">
          <a href="/" className="flex items-center gap-2">
            <img src="/logo.png" alt="Elan Swap" className="h-8 w-8" />
            <span className="self-center text-lg sm:text-xl font-semibold text-gray-900">Elan Swap</span>
          </a>
          <div className="flex items-center gap-3">
            {/* Login: refined ghost button with clearer border */}
            <a
              href="https://swap.elanbrands.net/login"
              className="inline-flex items-center rounded-md px-4 py-2 text-sm font-medium text-gray-800 bg-white/70 border border-gray-300 hover:bg-white hover:border-[var(--color-secondary)] hover:text-gray-900 shadow-sm transition-all duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-[var(--color-secondary)]"
            >
              Ingia
            </a>
            {/* Get Started: gradient with subtle border for definition */}
            <a
              href="https://swap.elanbrands.net/register"
              className="inline-flex items-center rounded-md px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-[var(--color-primary)] to-[var(--color-secondary)] border border-white/30 hover:border-white/50 shadow-md hover:shadow-lg hover:brightness-105 active:brightness-95 transition-all duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-[var(--color-secondary)]"
            >
              Jisajili
            </a>
          </div>
        </div>
      </nav>

      {/* Secondary menu bar */}
      <nav className="bg-gray-50 border-b border-gray-200">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex items-center h-12">
            <ul className="flex flex-row font-medium space-x-1 sm:space-x-2 text-sm">
              <li>
                <a
                  href="/"
                  className="relative inline-flex items-center rounded-md px-3 py-2 text-gray-700 hover:text-gray-900 hover:bg-white shadow-sm/0 hover:shadow-sm transition-all duration-200 group"
                >
                  Nyumbani
                  <span className="pointer-events-none absolute left-3 right-3 -bottom-0.5 h-0.5 bg-[var(--color-primary)] scale-x-0 group-hover:scale-x-100 origin-left transition-transform duration-200" />
                </a>
              </li>
              <li>
                <a
                  href="/about"
                  className="relative inline-flex items-center rounded-md px-3 py-2 text-gray-700 hover:text-gray-900 hover:bg-white shadow-sm/0 hover:shadow-sm transition-all duration-200 group"
                >
                  Kuhusu Sisi
                  <span className="pointer-events-none absolute left-3 right-3 -bottom-0.5 h-0.5 bg-[var(--color-primary)] scale-x-0 group-hover:scale-x-100 origin-left transition-transform duration-200" />
                </a>
              </li>
              <li>
                <a
                  href="/regions"
                  className="relative inline-flex items-center rounded-md px-3 py-2 text-gray-700 hover:text-gray-900 hover:bg-white shadow-sm/0 hover:shadow-sm transition-all duration-200 group"
                >
                  Mikoa
                  <span className="pointer-events-none absolute left-3 right-3 -bottom-0.5 h-0.5 bg-[var(--color-primary)] scale-x-0 group-hover:scale-x-100 origin-left transition-transform duration-200" />
                </a>
              </li>
              <li>
                <a
                  href="/features"
                  className="relative inline-flex items-center rounded-md px-3 py-2 text-gray-700 hover:text-gray-900 hover:bg-white shadow-sm/0 hover:shadow-sm transition-all duration-200 group"
                >
                  Vipengele
                  <span className="pointer-events-none absolute left-3 right-3 -bottom-0.5 h-0.5 bg-[var(--color-primary)] scale-x-0 group-hover:scale-x-100 origin-left transition-transform duration-200" />
                </a>
              </li>
              <li>
                <a
                  href="/contact"
                  className="relative inline-flex items-center rounded-md px-3 py-2 text-gray-700 hover:text-gray-900 hover:bg-white shadow-sm/0 hover:shadow-sm transition-all duration-200 group"
                >
                  Mawasiliano
                  <span className="pointer-events-none absolute left-3 right-3 -bottom-0.5 h-0.5 bg-[var(--color-primary)] scale-x-0 group-hover:scale-x-100 origin-left transition-transform duration-200" />
                </a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
    </header>
  );
}
