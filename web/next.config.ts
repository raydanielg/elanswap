import type { NextConfig } from "next";

const nextConfig: NextConfig = {
  eslint: {
    // Warning: This allows production builds to successfully complete even if
    // your project has ESLint errors.
    ignoreDuringBuilds: true,
  },
  // We serve the static export from Laravel at /out/, so make all asset URLs
  // relative to /out to avoid broken CSS/JS when not hosted at domain root.
  basePath: '/out',
  assetPrefix: '/out',
  output: 'export',
  trailingSlash: true,
  images: {
    unoptimized: true
  }
};

export default nextConfig;
