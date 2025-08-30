export const dynamic = "force-dynamic";

export async function GET() {
  try {
    const base = process.env.NEXT_PUBLIC_BACKEND_URL?.replace(/\/$/, "") || "https://www.swap.elanbrands.net";
    const res = await fetch(`${base}/api/categories`, {
      // Pass through cookies if same-origin is used in prod; optional
      // credentials: "include",
      // Revalidate each request
      cache: "no-store",
      headers: { Accept: "application/json" },
    });

    if (!res.ok) {
      return new Response(JSON.stringify({ ok: false, error: `Upstream ${res.status}` }), {
        status: 502,
        headers: { "content-type": "application/json" },
      });
    }

    const data = await res.json();
    return new Response(JSON.stringify(data), {
      status: 200,
      headers: { "content-type": "application/json" },
    });
  } catch (e: any) {
    return new Response(JSON.stringify({ ok: false, error: e?.message || "proxy_failed" }), {
      status: 500,
      headers: { "content-type": "application/json" },
    });
  }
}
