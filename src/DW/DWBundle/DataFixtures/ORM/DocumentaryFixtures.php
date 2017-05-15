<?php

namespace FL\FifaLeague\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use DW\DWBundle\Entity\Documentary;

class DocumentaryFixtures extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    private $container;
    private $manager;

    public function setContainer(ContainerInterface $container = null)
    {
    	$this->container = $container;
    }
    
	public function load(ObjectManager $manager)
	{
        $this->manager = $manager;

        $categoryTechnology = $this->getReference('technology');
        $categoryScience = $this->getReference('science');
        $categoryEnvironment = $this->getReference('environment');
        $categoryNature = $this->getReference('nature');
        $categoryMedia = $this->getReference('media');
        $categorySociety = $this->getReference('society');
        $categoryEconomics = $this->getReference('economics');
        $categoryHistory = $this->getReference('history');
        $categoryConspiracy = $this->getReference('conspiracy');

        $documentary1 = $this->createDocumentary(
            "The Pirate Bay Away From Keyboard",
            "It�s the day before the trial starts. Fredrik packs a computer into a rusty old Volvo. Along with his Pirate Bay co-founders, he faces $13 million in damage claims to Hollywood in a copyright infringement case. Fredrik is on his way to install a new computer in the secret server hall. This is where the world�s largest file sharing site is hidden.When the hacker prodigy Gottfrid, the internet activist Peter and the network nerd Fredrik are found guilty, they are confronted with the reality of life offline � away from keyboard. But deep down in dark data centres, clandestine computers quietly continue to duplicate files.",
            "the-pirate-bay-away-from-keyboard",
            3455,
            "the-pirate-bay-away-from-the-keyboard.jpg",
            84,
            $categoryTechnology,
            "2013-02-09 22:00:00");

        $documentary2 = $this->createDocumentary(
            "The Mars Underground",
            "This film captures the spirit of�who refuse to let their dreams be put on hold by a slumbering space program. Their passionate urge to walk the soil of an alien world is infectious and inspirational. This film is the manifesto of the new space revolution.

		Leading aerospace engineer and Mars Society President Dr. Robert Zubrin has a dream. He wants to get in the next ten years.

		Now, with the advent of a revolutionary plan, Mars Direct, Dr. Zubrin shows how we can use present day technology and natural resources on Mars to make human settlement possible. But can he win over the skeptics at NASA and the wider world?

		<em>The Mars Underground</em>�is a landmark documentary that follows Dr. Zubrin and his team as they try to bring this incredible dream to life. Through spellbinding animation, the film takes us on a daring first journey to the Red Planet and envisions a future Mars teeming with life and terraformed into a blue world.",
            "the-mars-underground",
            4445,
            "the-mars-underground.jpg",
            55,
            $categoryScience,
            "2013-02-08 22:00:00"
        );

        $documentary3 = $this->createDocumentary(
            "The Plastic Cow",
            "A 34-minute documentary about animal rights, the film looks at the impact of our almost complete dependence on plastic bags, which we use and discard carelessly every day, often to dispose our garbage and kitchen waste.

Not only are these bags a huge environmental threat, they end-up in the stomachs of cows, who, either because they’ve been discarded because they’re not milking at the time or because the dairy owner is unwilling to look after them, have to fend for themselves and forage for food, which, like other scavengers, they find in community garbage dumps. Owing to their complex digestive systems, these bags, which they consume whole for the food they contain, get trapped inside their stomachs forever and, eventually, lead to painful death. The film is also a comment on the religious hypocrisy of the cult of the holy cow.",
            "the-plastic-cow",
            5210,
            "the-plastic-cow.jpg",
            34,
            $categoryEnvironment,
            "2013-02-04 22:00:00"
        );

        $documentary4 = $this->createDocumentary(
            "Greening the Island of the Gods",
            "Bali does have a garbage problem.  But it also has the solutions.  Garbage is increasingly becoming a concern for locals and tourists alike, detracting from the natural beauty of Bali.  Illegal dumping, burning of garbage, and a lack of access to recycling facilities means that Bali’s air, land and water, and all those who live, work and play in it, battle pollution daily.  But the problem isn’t hopeless- it’s one we can tackle together.  And, in fact, we already are!
In this project, PSP highlights the best practices and sustainable solutions being implemented to address Bali’s garbage crisis, with contributions from diverse sectors of Bali’s community, ranging from local banjars to non-profit organizations to tourism professionals and waste management experts.  Especially in light of recent bad press resulting from Bali’s garbage situation, now is the time to show that we are addressing these issues to get Bali back to the natural paradise it’s known for being.  For the health of the local population, the environment, and the economy of tourism, taking positive and proactive action is key to the sustainability of Bali on all levels.
PSP has just released the English Language version of the film, is currently in the post-production stage for the Indonesian version of this project. Join the Green School’s Gr. 6 class and the Rotary Club Ubud Sunset and help make this project effect sustainable change! Read our proposal to learn more about the support we require, and how your donation can help to green the island of the Gods.",
            "greening-the-island-of-the-gods",
            2000,
            "greening-the-island-of-the-gods.jpg",
            32,
            $categoryEnvironment,
            "2013-02-04 22:00:00"
        );

        $documentary5 = $this->createDocumentary(
            "FRANKEENSTEER",
            "FRANKENSTEER is a disturbing yet compelling documentary that reveals how the ordinary cow is being transformed into an antibiotic dependent, hormone-laced potential carrier of toxic bacteria, all in the name of cheaper food.

    The beef industry, supported by North American government agencies and pharmaceutical companies, has engaged in an on-going experiment to create the perfect food machine to increase speed of production and reduce the cost of manufacture. But there is a price in producing a cheap industrial product. This benign, grazing herbivore has undergone a radical rethinking in how it’s raised, fed and slaughtered, including recent changes in inspection rules have shifted the responsibility for food safety from government inspectors to the people on the floor who do the slaughtering and packing.

        FRANKENSTEER reveals some startling facts. Every year 50% of the total tonnage of antibiotics used in Canada ends up in livestock. And every year cattle raised in massive feedlots are routinely dosed with antibiotics even if they are not sick. For public health safety reasons during the current BSE (Mad Cow disease) crisis, North American health officials have labeled certain parts of the cow as bio-hazardous products and have ordered that they be handled accordingly.

And consumers, by and large, are totally unaware of the dangers lurking in their beloved steaks, ribs and, most especially, hamburgers.",
            "frankensteer",
            4400,
            "frankensteer.jpg",
            92,
            $categoryEnvironment,
            "2013-01-19 22:00:00"
        );

        $documentary6 = $this->createDocumentary(
            "No Impact Man",
            "Colin Beavan decides to completely eliminate his personal impact on the environment for the next year.

It means eating vegetarian, buying only local food, and turning off the refrigerator. It also means no elevators, no television, no cars, busses, or airplanes, no toxic cleaning products, no electricity, no material consumption, and no garbage.

No problem – at least for Colin – but he and his family live in Manhattan. So when his espresso-guzzling, retail-worshipping wife Michelle and their two-year-old daughter are dragged into the fray, the No Impact Project has an unforeseen impact of its own.

Laura Gabbert and Justin Schein’s film provides an intriguing inside look into the experiment that became a national fascination and media sensation, while examining the familial strains and strengthened bonds that result from Colin and Michelle’s struggle with their radical lifestyle change.",
            "no-impact-man",
            3680,
            "no-impact-man.jpg",
            91,
            $categoryEnvironment,
            "2013-01-18 22:00:00"
        );

        $documentary7 = $this->createDocumentary(
            "The Big Fix",
            "On April 22, 2010 the Deepwater Horizon offshore drilling rig run by BP sunk into the Gulf of Mexico creating the worst oil spill in history. Until the oil well was killed on September 19, 779,037,744 liters of crude oil and over 7,000,000 liters of chemical dispersant spread into the sea. By exposing the root causes of the spill filmmakers Josh and Rebecca Tickell uncover a vast network of corruption. The Big Fix is a damning indictment of a system of government led by a powerful and secretive oligarchy that puts the pursuit of profit over all other human and environmental needs.",
            "the-big-fix",
            7000,
            "the-big-fix.jpg",
            90,
            $categoryEnvironment,
            "2013-01-18 22:00:00"
        );

        $documentary8 = $this->createDocumentary(
            "Growing Change: A Journey Inside Venezuela’s Food Revolution",
            "Growing Change follows the filmmaker’s journey to understand why current food systems leave hundreds of millions of people in hunger.  It’s a journey to understand how the world will feed itself in the future in the face of major environmental challenges.

The documentary begins with an investigation of the 2008 global food crisis, looking at the long-term underlying causes. Will expanding large-scale, energy-intensive agriculture, be the solution or re problems? If we already produce enough food to feed the world why do so many people go hungry?

After hearing about efforts in Venezuela to develop a more equitable and sustainable food and agriculture system, the filmmaker heads there to see if it’s working and find out what we might be able to learn from this giant experiment.

We meet people in the cities and in the countryside and learn that while Venezuela once had a strong agriculture sector it was left behind as the country became a major oil exporting economy in the 20th century. After decades of urbanisation, government neglect for agriculture, and dependent on food imports, Venezuela faced a food crisis of its own. In may ways the country was a microcosm of the challenges facing much of the world today.

But the documentary takes us through a new food system as it’s being constructed almost from scratch.

We meet farmers who are gaining access to land for the first time and working in cooperatives to break the country’s reliance on imports.

In lush costal villages we meet cocoa producers who are now protected against being paid below the minimum price and are now involved in the local processing of chocolate rather than just exporting raw beans.

We head out to sea with fisherfolk who are benefiting from new regulations that ban industrial trawling.

In the chaotic metropolis of Caracas we find urban gardens thriving and supplementing diets with fresh organic produce. We go inside shops where the urban poor have access to affordable food.

It’s all part of a country-wide process towards “food sovereignty”, driven by communities and the government. At the core of the process are principles of social justice and sustainability.

It’s an inspirational story full of lively characters, thought provoking insights, stunning scenery and ideas to transform the food system.",
            "growing-change",
            3100,
            "growing-change.jpg",
            60,
            $categoryEnvironment,
            "2013-01-14 22:00:00"
        );

        $documentary9 = $this->createDocumentary(
            "Lolita: Slave to Entertainment",
            "This Provocative and Revealing must-see documentary uniquely addresses man’s relationship with wildlife. It speaks not only to animal lovers and activists, but to anyone at all who may have been duped by marine theme park propaganda. In fact, this is the film that an entire industry would rather you not see. And whether you like it or not, Lolita: Slave to Entertainment is assured to ignite conversation — if not heated debate.

When Two Species Collide in the Icy Waters of Puget Sound a Storm of Epic Proportions is Unleashed. Man versus nature; in the summer of 1970 a barbaric hunt kills five orca whales and destroys the lives of countless others. Six young orcas are ripped away from their family, sold to marine parks, and shipped across the world to enter into a life of slavery. Three decades later only one survives. And she just so happens to be Miami’s biggest performer.

Lolita: Slave to Entertainment is a stirring wake up call. For those who have visited a marine park, for those who think they might do so in the future, and for those who simply wish to know the truth about performing marine mammals, this film is a “must see.” ~James Laveck Tribe of Heart (Producer of the award winning doc. The Witness)

Since that fateful day in 1970, waves of controversy have pounded both shores of the US as freedom fighters from across the globe battle for her liberation. It is a story of beauty, grace, passion, respect, exploitation, greed, prejudice, and domination. Disturbing footage of marine mammal captures and alarming interviews with former “Flipper” trainer Ric O’Barry, marine mammal specialist Ken Balcomb, animal sociologist Howard Garrett, animal advocate and President of Ocean Drive Magazine Jerry Powers, and former whale hunter John Crowe.",
            "lolita-slave-to-entertainment",
            4790,
            "lolita-slave-to-entertainment.jpg",
            58,
            $categoryNature,
            " 2013-01-13 21:00:00"
        );

        $documentary10 = $this->createDocumentary(
            "The Electronic Storyteller: TV & the Cultivation of Values",
            "Gerbner clearly and comprehensively outlines the way in which the universal story-telling function of human societies has been colonized by corporate media.

Drawing upon the path-breaking research of the Cultural Indicators Project, Gerbner outlines, in a comprehensive and clear fashion, the way in which the universal storytelling function of human societies has been colonized by corporate media in the modern world. Making a distinction between “effect” and his own theory of “cultivation,” he explains the role the media environment plays in how we think about ourselves and the way the world works.

Through a concrete focus on the stories of gender, class, and race, Gerbner provides us with an analytical framework to understand what is at stake in the debates about the media.",
            "the-electronic-storyteller",
            2280,
            "electronicstoryteller.jpg",
            32,
            $categoryMedia,
            "2013-01-13 21:00:00"
        );

        $documentary11 = $this->createDocumentary(
            "How To Start A Revolution",
            "Half a world away from Cairo’s Tahrir Square, an ageing American intellectual shuffles around his cluttered terrace house in a working-class Boston neighbourhood. His name is Gene Sharp. White-haired and now in his mid-eighties, he grows orchids, he has yet to master the internet and he hardly seems like a dangerous man. But for the world’s dictators his ideas can be the catalyst for the end of their regime.

Few people outside the world of academia have ever heard his name, but his writings on nonviolent revolution (most notably ‘From Dictatorship to Democracy’, a 93-page, 198-step guide to toppling dictators, available free for download in 40 languages) have inspired a new generation of protesters living under authoritarian regimes who yearn for democratic freedom.

His ideas have taken root in places as far apart as Burma, Thailand, Bosnia, Estonia, Iran, Indonesia, Zimbabwe, Venezuela and now in Syria, Egypt and elsewhere in the Middle East as old orders crumble amidst the protests of their disgruntled citizens.
This new film HOW TO START A REVOLUTION reveals how Gene’s ideas work in action. The film uses extended interviews with Gene himself, his assistant, his followers and leaders of revolutionary movements worldwide, as well as user-generated content from around the globe, to reveal the power of nonviolent revolution on the streets.

The film, from first-time director Ruaridh Arrow, profiles Gene and his followers on three continents and has been filmed over the last 18 months.

Nobel Peace Prize nominee Gene Sharp is one of the globe’s greatest thinkers on nonviolent revolutions. His work over the last 50 years has been groundbreaking.

His seminal book, ‘From Dictatorship to Democracy’ has been the standard manual for leaders of ‘colour’ revolutions around the globe – it lists 198 steps to nonviolent regime change. He has been called the ‘Machiavelli of nonviolent struggle’, and called much worse by the regimes who have fallen as a result of his work. His book is available free online and has been translated into over 40 languages.
His methods have been used in democratic struggles in the Balkans, throughout Eastern Europe in Georgia, the Ukraine, in Indonesia, Burma and Iran. In 2009 the Iranian government charged protesters with following Gene Sharp’s tactics; the Tehran Times reported: According to the indictment a number of the accused “confessed that the post-election unrest was preplanned and the plan was following the timetable of the velvet revolution to the extent that over 100 stages of the 198 steps of Gene Sharp were implemented in the foiled velvet revolution.”

HOW TO START A REVOLUTION profiles Gene Sharp and his ally Retired U.S. Army Colonel Robert Helvey, who has used Gene’s methods to train activists as far afield as Venezuela, Burma and Belgrade, together with a number of the key leaders of nonviolent revolutions around the world all of who testify to the power of Gene’s work in practice. The film climaxes as the current insurrection in Egypt testifies to the power of Gene’s work as the action as unfolds on the streets of Cairo. Throughout the film is illustrated with user-generated content of protesters and activists filmed on mobile phones in the street in Egypt, Tunisia, Iran, Serbia and elsewhere in the world.
First-hand testimony from key players the Serbian revolution in 2010 to activists involved in the abortive Iranian social unrest in 2010 reveals a decade of Gene Sharp’s work in action on the streets of Belgrade and Tehran.

HOW TO START A REVOLUTION is a portrait of how one man’s thinking has contributed to the liberation of millions of oppressed people living under some of the most brutal dictatorships in the world and how his work in direct action and civil disobedience continues to be used today to topple dictators using the sheer force of nonviolent people power.",
            "how-to-start-a-revolution",
            1244,
            "how-to-start-a-revolution.jpg",
            52,
            $categorySociety,
            "2013-01-13 22:00:00"
        );

        $documentary12 = $this->createDocumentary(
            "We Got F*cked",
            "A feature length, not for for profit documentary that looks into the world of banking and fiat currency using scenes from various movies and media sources from across the galaxy. It is made to analyse the actions taken by government and the people in the midst of the financial crises. This feature length documentary is made by Dejavusion Productions and Lucas Media using a variety of sources, using inspiration from a wide spectrum the documentary is built into chapters and can be viewed at your leisure. The thoughts and expressions examined in this video do not necessarily reflect those that feature.",
            "we-got-fcked",
            146900,
            "",
            96,
            $categoryEconomics,
            "2013-01-13 22:00:00"
        );

        $documentary13 = $this->createDocumentary(
            "Slavery by Another Name",
            "Slavery by Another Name is a 90-minute documentary that challenges one of Americans’ most cherished assumptions: the belief that slavery in this country ended with the Emancipation Proclamation. The film tells how even as chattel slavery came to an end in the South in 1865, thousands of African Americans were pulled back into forced labor with shocking force and brutality.

It was a system in which men, often guilty of no crime at all, were arrested, compelled to work without pay, repeatedly bought and sold, and coerced to do the bidding of masters. Tolerated by both the North and South, forced labor lasted well into the 20th century.

For most Americans this is entirely new history. Slavery by Another Name gives voice to the largely forgotten victims and perpetrators of forced labor and features their descendants living today.",
            "slavery-by-another-name",
            5430,
            "",
            60,
            $categoryHistory,
            "2013-01-13 22:00:00"
        );

        $documentary14 = $this->createDocumentary(
            "Occupied Cascadia",
            "Occupied Cascadia is a documentary film both journalistic and expressionistic. Exploring the emerging understanding of bioregionalism within the lands and waters of the Northeast Pacific Rim, the filmmakers interweave intimate landscape portraits with human voices both ideological and indigenous. Stories from the land contrast critique of dominant culture, while an embrace of the radical unknown informs a re-birthed and growing culture of resistance. Filming began during the outset of the populist “Occupy” movement, and finished by joining the voices seeking to re-contextualize popular revolt within our life-world as a movement to decolonize, un-occupy, and re-inhabit the living Earth through deep understanding and identification with our specific bioregions (literally “Life-Place”).

Cascadia Matters is a collective effort of writers, artists, educators and media activists. We aim to highlight the emerging ideas, struggles and times of our bioregion and beyond. In order to effectively provide clean air and clean water for future generations, our attention must not only shift towards our bioregions, but also towards stopping corporate agendas within them. The diverse voices throughout this land have forged the way for many movements. We feel the growing necessity to inspire a unified culture of resistance. We are here to promote that dialogue through bioregional awareness.",
            "occupied-cascadia",
            3400,
            "",
            116,
            $categoryEnvironment,
            "2013-01-13 22:00:00"
        );

        $documentary15 = $this->createDocumentary(
            "2012 Crossing Over, A New Beginning",
            "Dec 21, 2012 is on everyone’s mind. What will it bring? Is it the end of the world? A new beginning for mankind? Or just another year on the calendar? 2012 Crossing Over, A New Beginning explores a spiritual perspective on the events of Dec 21, 2012.

This documentary investigates the galactic alignment, consciousness awakening, cycles of evolution, our binary star system with Sirius, the fear agenda in the media, who’s behind it, love vs fear and much more. The film is loaded with revelations of the current times we live in, from the astrologer and teacher Santos Bonacci, and spiritual leaders Bud Barber, and George Neo.

The participants in this documentary say that we’re at that time when great transition takes place. The society, the way we live now, is changing very rapidly. We have to go along with the change that’s happening as opposed to fear it and resist it. We’ve all heard about “2012.” It’s the doomsday. The world will end, the mankind will end.

Some people got this idea that things will stop, things will end in catastrophe, but actually none of the philosophies assign that. The agenda seems to be trying to put negative spin on it, to radiate fear out on the planet as opposed to love.",
            "2012-crossing-over",
            5100,
            "",
            104,
            $categoryConspiracy,
            "2013-01-13 22:00:00"
        );

		$manager->flush();

        $this->addReference('documentary1', $documentary1);
        $this->addReference('documentary2', $documentary2);
        $this->addReference('documentary3', $documentary3);
        $this->addReference('documentary4', $documentary4);
        $this->addReference('documentary5', $documentary5);
        $this->addReference('documentary6', $documentary6);
        $this->addReference('documentary7', $documentary7);
        $this->addReference('documentary8', $documentary8);
        $this->addReference('documentary9', $documentary9);
        $this->addReference('documentary10', $documentary10);
        $this->addReference('documentary11', $documentary11);
        $this->addReference('documentary12', $documentary12);
        $this->addReference('documentary13', $documentary13);
        $this->addReference('documentary14', $documentary14);
        $this->addReference('documentary15', $documentary15);
	}

    private function createDocumentary($title, $description, $slug, $views,
                                       $thumbnail, $length, $category, $created)
    {
        $documentary = new Documentary();
        $documentary->setTitle($title);
        $documentary->setDescription($description);
        $documentary->setStatus("publish");
        $documentary->setSlug($slug);
        $documentary->setViews($views);
        $documentary->setThumbnail($thumbnail);
        $documentary->setLength($length);
        $documentary->addCategorie($category);
        $documentary->setCreated(new \DateTime(date($created)));
        $documentary->setUpdated($documentary->getCreated());
        $this->manager->persist($documentary);
        return $documentary;
    }

	public function getOrder()
	{
		return 4;
	}
}