"use client";

import { useState } from "react";

export default function Contact() {
  const [name, setName] = useState("");
  const [email, setEmail] = useState("");
  const [message, setMessage] = useState("");
  const [busy, setBusy] = useState(false);
  const [sent, setSent] = useState<null | "ok" | "err">(null);
  const [errText, setErrText] = useState<string | null>(null);

  const [nlEmail, setNlEmail] = useState("");
  const [nlBusy, setNlBusy] = useState(false);
  const [nlDone, setNlDone] = useState<null | "ok" | "err">(null);

  const onSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setBusy(true);
    setSent(null);
    setErrText(null);
    try {
      const res = await fetch("https://www.swap.elanbrands.net/api/contact", {
        method: "POST",
        headers: { "content-type": "application/json" },
        body: JSON.stringify({ name, email, message, company: "" }), // company = honeypot
      });
      const json = await res.json();
      if (!res.ok || !json?.ok) throw new Error(json?.error || `HTTP ${res.status}`);
      setSent("ok");
      setName("");
      setEmail("");
      setMessage("");
    } catch (err: any) {
      setSent("err");
      setErrText(err?.message || "Failed to send");
    } finally {
      setBusy(false);
    }
  };

  const onSubscribe = async (e: React.FormEvent) => {
    e.preventDefault();
    setNlBusy(true);
    setNlDone(null);
    try {
      const res = await fetch("https://www.swap.elanbrands.net/api/newsletter", {
        method: "POST",
        headers: { "content-type": "application/json" },
        body: JSON.stringify({ email: nlEmail, website: "" }), // website = honeypot
      });
      const json = await res.json();
      if (!res.ok || !json?.ok) throw new Error(json?.error || `HTTP ${res.status}`);
      setNlDone("ok");
      setNlEmail("");
    } catch (err: any) {
      setNlDone("err");
    } finally {
      setNlBusy(false);
    }
  };

  return (
    <section id="contact" className="py-16 lg:py-24 border-t border-black/10 page-dark-bg">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-start justify-between gap-10">
          {/* Left: Details & Services */}
          <div className="flex-1 min-w-0">
            <h2 className="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">Contact Us</h2>
            <p className="mt-2 text-white/85">Tuna-jibu haraka. Tuma ujumbe au pigia simu.</p>

            <div className="mt-6 grid sm:grid-cols-2 gap-4">
              <div className="rounded-xl border border-white/15 bg-white/5 backdrop-blur p-4">
                <div className="text-white font-semibold">Contact Details</div>
                <ul className="mt-3 space-y-2 text-white/85 text-sm">
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

              <div className="rounded-xl border border-white/15 bg-white/5 backdrop-blur p-4">
                <div className="text-white font-semibold">More Services</div>
                <ul className="mt-3 grid grid-cols-2 gap-2 text-white/85 text-sm">
                  <li className="rounded-lg border border-white/10 bg-white/5 px-3 py-2">Consultation</li>
                  <li className="rounded-lg border border-white/10 bg-white/5 px-3 py-2">Custom Integrations</li>
                  <li className="rounded-lg border border-white/10 bg-white/5 px-3 py-2">Support & Training</li>
                  <li className="rounded-lg border border-white/10 bg-white/5 px-3 py-2">API Access</li>
                </ul>
              </div>
            </div>
          </div>

          {/* Right: Forms */}
          <div className="w-full max-w-xl">
            <div className="rounded-2xl border border-white/15 bg-white/10 backdrop-blur p-6 shadow">
              <h3 className="text-white font-semibold text-lg">Send us a message</h3>
              <form onSubmit={onSubmit} className="mt-4 grid gap-3">
                <div className="grid sm:grid-cols-2 gap-3">
                  <input className="w-full border border-white/15 bg-white/5 text-white placeholder-white/60 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[var(--color-secondary)]/50" placeholder="Your name" value={name} onChange={(e) => setName(e.target.value)} required />
                  <input className="w-full border border-white/15 bg-white/5 text-white placeholder-white/60 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[var(--color-secondary)]/50" placeholder="Your email" type="email" value={email} onChange={(e) => setEmail(e.target.value)} required />
                </div>
                <textarea className="w-full border border-white/15 bg-white/5 text-white placeholder-white/60 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[var(--color-secondary)]/50" placeholder="Message" rows={4} value={message} onChange={(e) => setMessage(e.target.value)} required />
                <button disabled={busy} type="submit" className="inline-flex items-center justify-center rounded-md bg-[var(--color-primary)] px-4 py-2 text-[var(--color-primary-foreground)] text-sm font-medium hover:opacity-90 disabled:opacity-60">
                  {busy ? "Sending..." : "Send Message"}
                </button>
                {sent === "ok" && <p className="text-emerald-300 text-sm">Asante! Tumepokea ujumbe wako.</p>}
                {sent === "err" && <p className="text-red-300 text-sm">Imeshindikana kutuma: {errText}</p>}
              </form>
            </div>

            <div className="mt-4 rounded-2xl border border-white/15 bg-white/10 backdrop-blur p-6 shadow">
              <h3 className="text-white font-semibold text-lg">Subscribe to our newsletter</h3>
              <form onSubmit={onSubscribe} className="mt-3 flex gap-2">
                <input className="flex-1 border border-white/15 bg-white/5 text-white placeholder-white/60 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[var(--color-secondary)]/50" placeholder="Email address" type="email" value={nlEmail} onChange={(e) => setNlEmail(e.target.value)} required />
                <button disabled={nlBusy} type="submit" className="inline-flex items-center justify-center rounded-md bg-white/20 px-4 py-2 text-white text-sm font-medium hover:bg-white/30 disabled:opacity-60">{nlBusy ? "Subscribing..." : "Subscribe"}</button>
              </form>
              {nlDone === "ok" && <p className="mt-2 text-emerald-300 text-sm">Umejiunga! Tutakutumia taarifa mpya.</p>}
              {nlDone === "err" && <p className="mt-2 text-red-300 text-sm">Imeshindikana. Tafadhali jaribu tena.</p>}
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}
