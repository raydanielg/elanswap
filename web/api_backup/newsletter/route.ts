export const dynamic = "force-dynamic";

function isValidEmail(email: string) {
  return /.+@.+\..+/.test(email);
}

export async function POST(req: Request) {
  try {
    const body = await req.json();
    const email = (body?.email || "").toString().trim();
    const hp = (body?.website || "").toString().trim(); // honeypot

    if (hp) {
      return new Response(JSON.stringify({ ok: true }), { status: 200, headers: { "content-type": "application/json" } });
    }

    if (!isValidEmail(email)) {
      return new Response(JSON.stringify({ ok: false, error: "invalid_email" }), { status: 400, headers: { "content-type": "application/json" } });
    }

    console.log("NEWSLETTER_SUBSCRIBE", { email, at: new Date().toISOString() });

    return new Response(JSON.stringify({ ok: true }), { status: 200, headers: { "content-type": "application/json" } });
  } catch (e: any) {
    return new Response(JSON.stringify({ ok: false, error: e?.message || "bad_request" }), { status: 500, headers: { "content-type": "application/json" } });
  }
}
