import Hero from "@/components/Hero";
import Features from "@/components/Features";
import Categories from "@/components/Categories";
import Regions from "@/components/Regions";
import Contact from "@/components/Contact";

export default function Home() {
  return (
    <div className="font-sans">
      <Hero />
      <Features />
      <Categories />
      <Regions />
      <Contact />
    </div>
  );
}
