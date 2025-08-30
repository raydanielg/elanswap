export default function Footer() {
  const year = new Date().getFullYear();
  return (
    <footer className="mt-16 border-t border-white/10 page-dark-bg" aria-labelledby="footer-heading">
      <h2 id="footer-heading" className="sr-only">Footer</h2>
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
          {/* Brand */}
          <div>
            <a href="/" className="inline-flex items-center gap-2">
              <img src="/logo.png" alt="Elan Swap" className="h-8 w-8 rounded" />
              <span className="text-white font-semibold">Elan Swap</span>
            </a>
            <p className="mt-3 text-sm text-white/75 leading-relaxed">
              Smart swaps for your next opportunity. Tupo kukupa urahisi wa kubadilishana vituo.
            </p>
            <div className="mt-4 flex items-center gap-3 text-white/80">
              <a aria-label="X/Twitter" href="#" className="hover:text-white/100 transition">
                <svg viewBox="0 0 24 24" className="h-5 w-5" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.49 11.24H16.17l-5.3-6.93-6.06 6.93H1.494l7.73-8.84L1 2.25h6.17l4.78 6.35 6.294-6.35Zm-1.16 19.5h1.833L7.01 3.123H5.05l12.034 18.627Z"/></svg>
              </a>
              <a aria-label="Facebook" href="#" className="hover:text-white/100 transition">
                <svg viewBox="0 0 24 24" className="h-5 w-5" fill="currentColor"><path d="M13.5 9H16l.5-3h-3V4.5c0-.86.173-1.5 1.5-1.5H16V0h-2c-2.485 0-4 1.343-4 3.818V6H8v3h2v9h3.5V9Z"/></svg>
              </a>
              <a aria-label="Instagram" href="#" className="hover:text-white/100 transition">
                <svg viewBox="0 0 24 24" className="h-5 w-5" fill="currentColor"><path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5Zm0 2a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3H7Zm5 3.5a5.5 5.5 0 1 1 0 11 5.5 5.5 0 0 1 0-11Zm0 2a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Zm5.75-.75a.75.75 0 1 1 0 1.5.75.75 0 0 1 0-1.5Z"/></svg>
              </a>
            </div>
          </div>

          {/* Quick Links */}
          <div>
            <div className="text-white font-semibold">Quick Links</div>
            <ul className="mt-3 space-y-2 text-sm">
              <li><a className="text-white/80 hover:text-white transition" href="/">Home</a></li>
              <li><a className="text-white/80 hover:text-white transition" href="/about">About Us</a></li>
              <li><a className="text-white/80 hover:text-white transition" href="/regions">Destinations</a></li>
              <li><a className="text-white/80 hover:text-white transition" href="/features">Features</a></li>
              <li><a className="text-white/80 hover:text-white transition" href="/contact">Contact</a></li>
            </ul>
          </div>

          {/* Resources */}
          <div>
            <div className="text-white font-semibold">Resources</div>
            <ul className="mt-3 space-y-2 text-sm">
              <li><a className="text-white/80 hover:text-white transition" href="/privacy">Privacy Policy</a></li>
              <li><a className="text-white/80 hover:text-white transition" href="/terms">Terms of Service</a></li>
              <li><a className="text-white/80 hover:text-white transition" href="/help">Help Center</a></li>
            </ul>
          </div>

          {/* Contact */}
          <div>
            <div className="text-white font-semibold">Get in touch</div>
            <ul className="mt-3 space-y-2 text-sm text-white/85">
              <li className="flex items-center gap-2">
                <svg viewBox="0 0 24 24" className="h-4 w-4" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M22 16.92V21a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2 4.18 2 2 0 0 1 4 2h4.09a2 2 0 0 1 2 1.72c.12.9.3 1.77.54 2.6a2 2 0 0 1-.45 2L9 9a16 16 0 0 0 6 6l.66-1.18a2 2 0 0 1 2-1c.83.24 1.7.42 2.6.54A2 2 0 0 1 22 16.92z"/></svg>
                +255 712 345 678
              </li>
              <li className="flex items-center gap-2">
                <svg viewBox="0 0 24 24" className="h-4 w-4" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M4 4h16v16H4z"/><path d="M22 6l-10 7L2 6"/></svg>
                support@elanswap.tz
              </li>
              <li className="flex items-center gap-2">
                <svg viewBox="0 0 24 24" className="h-4 w-4" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M21 10c0 7-9 12-9 12S3 17 3 10a9 9 0 1 1 18 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                Dar es Salaam, Tanzania
              </li>
            </ul>
          </div>
        </div>

        <div className="mt-10 pt-6 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-white/70">
          <p>© {year} Elan Swap. All rights reserved.</p>
          <p>Built with care — Hakimiliki.</p>
        </div>
      </div>
    </footer>
  );
}
