export const dynamic = "force-dynamic";

function isValidEmail(email: string) {
  return /.+@.+\..+/.test(email);
}

export async function POST(req: Request) {
  try {
    const body = await req.json();
    const name = (body?.name || "").toString().trim();
    const email = (body?.email || "").toString().trim();
    const message = (body?.message || "").toString().trim();
    const hp = (body?.company || "").toString().trim(); // honeypot field

    if (hp) {
      // bot detected
      return new Response(JSON.stringify({ ok: true }), { status: 200, headers: { "content-type": "application/json" } });
    }

    if (!name || !isValidEmail(email) || message.length < 5) {
      return new Response(JSON.stringify({ ok: false, error: "invalid_input" }), { status: 400, headers: { "content-type": "application/json" } });
    }

    // Simulate sending: log to server (plug email/SMS provider later)
    console.log("CONTACT_MESSAGE", { name, email, message, at: new Date().toISOString() });

    return new Response(JSON.stringify({ ok: true }), { status: 200, headers: { "content-type": "application/json" } });
  } catch (e: any) {
    return new Response(JSON.stringify({ ok: false, error: e?.message || "bad_request" }), { status: 500, headers: { "content-type": "application/json" } });
  }
}
