<?php
/**
 * Sample Data for blocks.
 *
 * @since 5.8.3
 */

namespace WPTravelEngine\Blocks;

class SampleData {

	public static function overview(): string {
		return 'Experience the thrill of hiking <a href="#">through</a> breathtaking landscapes, camping under the stars, and exploring hidden gems in the mountains.';
	}

	public static function trip_facts(): array {
		return array(
			array(
				'title'   => 'Trip Type',
				'icon'    => 'fas fa-user-group',
				'content' => 'Adventure',
			),
			array(
				'title'   => 'Trip Duration',
				'icon'    => 'fas fa-user-group',
				'content' => '10 Days',
			),
			array(
				'title'   => 'Group Size',
				'icon'    => 'fas fa-user-group',
				'content' => '10-15 People',
			),
			array(
				'title'   => 'Difficulty Level',
				'icon'    => 'fas fa-user-group',
				'content' => 'Moderate',
			),
			array(
				'title'   => 'Max Altitude',
				'icon'    => 'fas fa-user-group',
				'content' => '3,210m',
			),
			array(
				'title'   => 'Accommodation',
				'icon'    => 'fas fa-user-group',
				'content' => 'Teahouse',
			),
			array(
				'title'   => 'Meals',
				'icon'    => 'fas fa-user-group',
				'content' => 'Breakfast, Lunch, Dinner',
			),
			array(
				'title'   => 'Transportation',
				'icon'    => 'fas fa-user-group',
				'content' => 'Private Vehicle',
			),
			array(
				'title'   => 'Best Season',
				'icon'    => 'fas fa-user-group',
				'content' => 'March to May, September to November',
			),
		);
	}

	public static function itinerary() {
		return array(
			array(
				'title'                  => 'Kathmandu to Pokhara',
				'days_label'             => '',
				'content'                => 'Arrive at Tribhuwan International Airport, Kathmandu, you are welcomed by the team and then you will be transferred to your hotel. This trail goes through Ghorepani Poon Hill. Normally, the trek starts like Pokhara to Nayapul and ends like Phedi to Pokhara. While early travel tended to be slower, more dangerous, and more dominated by trade and migration, cultural and technological advances over many years have tended to mean that travel has become easier and more accessible. The evolution of technology in such diverse fields as horse tack and bullet trains has contributed to this trend.				',
				'duration'               => '3',
				'duration_type'          => 'hour',
				'sleep_modes'            => '3 Star Hotel',
				'sleep_mode_description' => 'You can add details and images related to the accommodation. For Example, Hilton Hotels & Resorts (formerly known as Hilton Hotels) is a global brand of full-service hotels and resorts and the flagship brand of American multinational hospitality company Hilton.',
				'itinerary_image'        => '',
				'meals_included'         => array( 'breakfast', 'lunch' ),
			),
			array(
				'title'                  => 'Drive to Nayapul and trek to Ulleri',
				'days_label'             => '',
				'content'                => 'While early travel tended to be slower, more dangerous, and more dominated by trade and migration, cultural and technological advances over many years have tended to mean that travel has become easier and more accessible. The evolution of technology in such diverse fields as horse tack and bullet trains has contributed to this trend.',
				'duration'               => '6',
				'duration_type'          => 'hour',
				'sleep_modes'            => '5 Star Hotel',
				'sleep_mode_description' => 'You can add details and images related to the accommodation. For Example, Hilton Hotels & Resorts (formerly known as Hilton Hotels) is a global brand of full-service hotels and resorts and the flagship brand of American multinational hospitality company Hilton.',
				'itinerary_image'        => '',
				'meals_included'         => array( 'breakfast', 'lunch' ),
			),
			array(
				'title'                  => 'Trek to Gorepani',
				'days_label'             => '',
				'content'                => 'The Palace of Fifty five Windows: This magnificent palace was built during the reign of King Yakshya Malla in A.D. 1427 and was subsequently remodeled by King Bhupatindra Malla in the seventeenth century. Among the brick walls with their gracious setting and sculptural design, is a balcony with Fifty five Windows, considered to be a unique masterpiece of woodcarving.',
				'duration'               => '4',
				'duration_type'          => 'hour',
				'sleep_modes'            => 'Tea House',
				'sleep_mode_description' => 'You can add details and images related to the accommodation. For Example, Hilton Hotels & Resorts (formerly known as Hilton Hotels) is a global brand of full-service hotels and resorts and the flagship brand of American multinational hospitality company Hilton.',
				'itinerary_image'        => '',
				'meals_included'         => array( 'breakfast', 'lunch' ),
			),
			array(
				'title'                  => 'Early trek to Poon Hill, Back to Ghorepani and Trek to Tadapani',
				'days_label'             => '',
				'content'                => 'Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean. A small river named Duden flows by their place and supplies it with the necessary regelialia.',
				'duration'               => '7',
				'duration_type'          => 'hour',
				'sleep_modes'            => '',
				'sleep_mode_description' => '',
				'itinerary_image'        => '',
				'meals_included'         => array( 'breakfast', 'lunch', 'dinner' ),
			),
			array(
				'title'                  => 'Tadapani to Chomrong',
				'days_label'             => '',
				'content'                => 'Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of  Dan decided to leave for the far World of Grammar.',
				'duration'               => '5',
				'duration_type'          => 'hour',
				'sleep_modes'            => '',
				'sleep_mode_description' => '',
				'itinerary_image'        => '',
				'meals_included'         => array( 'breakfast', 'lunch', 'dinner' ),
			),
			array(
				'title'                  => 'Chomrong to Dobhan (Dovan)',
				'days_label'             => '',
				'content'                => 'The Big Oxmox advised her not to do so because there were thousands of bad Commas, wild Question Marks and devious Semikoli, but the Little Blind Text didn’t listen.',
				'duration'               => '8',
				'duration_type'          => 'hour',
				'sleep_modes'            => '',
				'sleep_mode_description' => '',
				'itinerary_image'        => '',
				'meals_included'         => array( 'breakfast', 'lunch', 'dinner' ),
			),
			array(
				'title'                  => 'Dovan to Deurali',
				'days_label'             => '',
				'content'                => 'When she reached the first hills of the Italic Mountains, she had a last view back on the skyline of her hometown Bookmarksgrove, the headline of Alphabet Village and the subline of her own road, the Line Lane. Pityful a rethoric question ran over her cheek.',
				'duration'               => '6',
				'duration_type'          => 'hour',
				'sleep_modes'            => '',
				'sleep_mode_description' => '',
				'itinerary_image'        => '',
				'meals_included'         => array( 'breakfast', 'lunch', 'dinner' ),
			),
		);
	}

	public static function faqs(): array {
		return array(
			array(
				'question' => 'What is the best time to visit Nepal?',
				'answer'   => 'The best time to visit Nepal is during the spring (March to May) and autumn (September to November). During these times, the weather is pleasant and the skies are clear, making it ideal for trekking. The monsoon season (June to August) is also a good time to visit Nepal, as the rain washes away the dust and pollution, leaving the air fresh and clean. However, the trails can be muddy and slippery during this time, so it is best to avoid trekking in the mountains.',
			),
			array(
				'question' => 'What is the currency of Nepal?',
				'answer'   => 'The currency of Nepal is the Nepalese Rupee (NPR). The exchange rate is approximately 1 USD = 120 NPR. It is best to carry small denominations of currency, as it can be difficult to get change for larger bills. Credit cards are accepted in most hotels, restaurants, and shops in the major cities, but it is best to carry cash for smaller purchases and in rural areas.',
			),
			array(
				'question' => 'Do I need a visa to visit Nepal?',
				'answer'   => 'Yes, all foreign nationals (except Indian citizens) require a visa to enter Nepal. Visas can be obtained on arrival at the Tribhuvan International Airport in Kathmandu, or at the border crossings with India and Tibet. The cost of a tourist visa is USD 30 for 15 days, USD 50 for 30 days, and USD 125 for 90 days. You will need to provide a passport-sized photo and the exact amount in USD for the visa fee. You can also apply for a visa online before you travel.',
			),
			array(
				'question' => 'Is it safe to travel to Nepal?',
				'answer'   => 'Yes, Nepal is a safe and welcoming destination for travelers. The people of Nepal are known for their hospitality and friendliness, and the country has a low crime rate. However, it is always important to take precautions when traveling, such as keeping your valuables secure and being aware of your surroundings. It is also a good idea to check the latest travel advisories before you go, and to follow the advice of local authorities and tour operators.',
			),
		);
	}

	public static function cost_includes(): array {
		return array(
			'Airport pick up and drop off',
			'3 nights in a 3-star hotel in Kathmandu with breakfast',
			'Welcome and farewell dinners',
			'All accommodation and meals during the trek',
			'All ground transportation on a comfortable private vehicle as per the itinerary',
			'An experienced, English-speaking and government-licensed trek leader and assistant trek leader (4 trekkers: 1 assistant guide)',
			'Porter service (2 trekkers: 1 porter)',
			'All necessary paperwork and permits (National park permit, TIMS)',
			'A comprehensive medical kit',
			'All government and local taxes',
		);
	}

	public static function cost_excludes(): array {
		return array(
			'Nepalese visa fee',
			'International airfare to and from Kathmandu',
			'Excess baggage charges',
			'Extra night accommodation in Kathmandu because of early arrival, late departure, early return from the mountain (due to any reason) than the scheduled itinerary',
			'Lunch and evening meals in Kathmandu',
			'Travel and rescue insurance',
			'Personal expenses (phone calls, laundry, bar bills, battery recharge, extra porters, bottle or boiled water, shower, etc)',
			'Tips for guides and porters',
		);
	}

	public static function highlights(): array {
		return array(
			'Trek to the world-famous Everest Base Camp',
			'Enjoy the amazing view of the Himalayas from Kala Patthar',
			'Travel through the Sherpa villages of Namche, Khumjung, Khunde, and Dingboche',
			'Visit Tengboche the biggest and oldest monastery n the region.',
		);
	}

	public static function gallery(): array {
		return array(
			array(
				esc_url( plugins_url( 'includes/classes/Blocks/assets/sample.png', \WP_TRAVEL_ENGINE_FILE_PATH ) ),
				'sample-alt',
			),
		);
	}

	public static function map(): array {
		return array(
			'iframe'    => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3500.4109563529746!2d83.86043231542989!3d28.67735098880116!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39be199c2d97b7a3%3A0x36fca1c48eeffc99!2z4KSk4KS_4KSy4KS_4KSa4KWLIOCkpOCkvuCksiDgpKjgpYfgpKrgpL7gpLI!5e0!3m2!1sen!2snp!4v1628845389546!5m2!1sen!2snp" width="600" height="450" style="border:0" allowfullscreen="" loading="lazy"></iframe>',
			'image_url' => esc_url( plugins_url( 'includes/classes/Blocks/assets/sample.png', \WP_TRAVEL_ENGINE_FILE_PATH ) ),
		);
	}

	public static function duration(): array {
		return array(
			'duration'      => '10',
			'duration_unit' => 'days',
		);
	}

	public static function prices() {
		return array(
			array(
				'price'      => 1800,
				'sale_price' => 1400,
				'has_sale'   => true,
				'per_label'  => 'Adult - Sample',
			),
			array(
				'price'      => 1000,
				'sale_price' => 600,
				'has_sale'   => false,
				'per_label'  => 'Child - Sample',
			),
		);
	}

	public static function fsd() {
		return array(
			'date1' => array(
				'content_id'   => 'date1',
				'start_date'   => 'March 2, 2024',
				'end_date'     => 'March 4, 2024',
				'availability' => 'Guaranteed',
				'price'        => 1440,
				'space'        => 10,
			),
			'date2' => array(
				'content_id'   => 'date2',
				'start_date'   => 'April 5, 2024',
				'end_date'     => 'April 7, 2024',
				'availability' => 'Guaranteed',
				'price'        => 1200,
				'space'        => 8,
			),
		);
	}

	public static function reviews_count(): string {
		return '1';
	}

	public static function ratings(): string {
		return '5';
	}

	public static function star_ratings(): string {
		return '5';
	}

	public static function star_bars(): array {
		return array(
			'very-happy' => array(
				'percent' => 100,
				'emoji'   => Icons::very_happy(),
				'text'    => 'Excellent',
			),
			'happy'      => array(
				'percent' => 80,
				'emoji'   => Icons::happy(),
				'text'    => 'Very Good',
			),
			'neutral'    => array(
				'percent' => 70,
				'emoji'   => Icons::confused(),
				'text'    => 'Average',
			),
			'sad'        => array(
				'percent' => 60,
				'emoji'   => Icons::sad(),
				'text'    => 'Poor',
			),
			'angry'      => array(
				'percent' => 50,
				'emoji'   => Icons::angry(),
				'text'    => 'Terrible',
			),
		);
	}

	public static function global_highlights(): array {
		return array(
			array(
				'highlight' => 'Unbeatable Value Assurance',
				'help'      => 'Discover extraordinary adventures',
			),
			array(
				'highlight' => 'Effortless Reservation Process',
				'help'      => 'No booking hassles',
			),
			array(
				'highlight' => 'Transparent Pricing, Zero Surprises',
				'help'      => 'No hidden costs',
			),
			array(
				'highlight' => 'Expertise Beyond Measure',
				'help'      => 'Team of seasoned experts',
			),
			array(
				'highlight' => 'Your Joy, Our Priority',
				'help'      => 'Happiness Commitment',
			),
		);
	}
}
