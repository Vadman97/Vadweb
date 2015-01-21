import java.applet.AudioClip;
import java.awt.Color;
import java.awt.Dimension;
import java.awt.Font;
import java.awt.Graphics;
import java.awt.Image;
import java.awt.event.KeyEvent;
import java.awt.event.KeyListener;
import java.awt.event.MouseEvent;
import java.awt.event.MouseListener;
import java.awt.event.MouseMotionListener;
import java.net.MalformedURLException;
import java.net.URL;
import java.util.ArrayList;
import java.util.Random;
import javax.swing.JApplet;

@SuppressWarnings("unchecked")
public class Solitaire extends JApplet implements MouseListener, KeyListener, MouseMotionListener
{
	// TODO Fix winning
	// TODO Add emergency spot for cards to be put there when accidentally
	// clicked as a placeholder
	// TODO Add automatic fillup of completed if done opening all cards
	// TODO If you doubleclick a card, it pops into the right completed deck if
	// possible
	// TODO Fix timer so it stops if won
	// TODO Fix taking up so much of the screen with piles
	// TODO Fix card clicking regions messed up
	
	//TODO ADD WIN MECHANICS
	public Font					bigFont			= new Font("Calibri", 22, 22);
	public Font					hugeFont		= new Font("Calibri", 50, 50);
	public Font					massiveFont		= new Font("Calibri", 100, 100);
	Random						r				= new Random();
	Image						offscreen, cardBack, background;
	Dimension					dim;
	Graphics					bg;
	URL							imgLocation;
	boolean						startScreen		= true;
	int							shuffleCounter	= 101;
	int							numPiles		= 7;
	int							mouseX, mouseY = 0;
	int							cardWidth		= (int) (73 * 1.4);
	int							cardHeight		= (int) (97 * 1.4);
	int							offset			= 200;
	int							p, c, z = 0;
	int							cardYShift		= 100;
	long						timeStart;

	Color						random;
	public static AudioClip		ac;
	public Image				cardImages[][]	= new Image[15][5];
	public ArrayList<Card>		shuffledDeck	= new ArrayList<Card>();
	public ArrayList<Card>		unshuffledDeck	= new ArrayList<Card>();
	public ArrayList<Card>[]	piles			= new ArrayList[numPiles];
	public ArrayList<Card>		hand			= new ArrayList<Card>();
	public ArrayList<Card>[]	completed		= new ArrayList[4];
	public ArrayList<Card>		drawn			= new ArrayList<Card>();
	public Card					tempSlot;

	public void init()
	{
		resize(1800, 900);
		setFocusable(true);
		addMouseListener(this);
		addMouseMotionListener(this);
		addKeyListener(this);
		dim = getSize();
		offscreen = createImage(dim.width, dim.height);
		bg = offscreen.getGraphics();
		for (int i = 0; i < numPiles; i++)
			piles[i] = new ArrayList<Card>();
		for (int i = 0; i < completed.length; i++)
			completed[i] = new ArrayList<Card>();
		try
		{
			imgLocation = new URL("http://vadweb.us/javaApplet/Solitaire/card_imgs/");
			// Mr. Greene, update this to your picture location
			// imgLocation = new URL(getDocumentBase().toString());
		} catch (MalformedURLException e)
		{
			System.out.println(e.getMessage());
		}
		cardBack = getImage(imgLocation, "rear.jpg");
		background = getImage(imgLocation, "background.jpg");
		ac = getAudioClip(imgLocation, "rick.wav");

		initDeck();
		initImages();
		shuffleDeck();
		dealCards();
	}

	public void restart()
	{
		shuffleCounter = 101;
		numPiles = 7;
		mouseX = 0;
		mouseY = 0;
		cardWidth = (int) (73 * 1.4);
		cardHeight = (int) (97 * 1.4);
		p = 0;
		c = 0;
		z = 0;

		shuffledDeck.clear();
		unshuffledDeck.clear();
		for (int i = 0; i < piles.length; i++)
		{
			piles[i].clear();
		}
		hand.clear();
		for (int i = 0; i < completed.length; i++)
		{
			completed[i].clear();
		}
		drawn.clear();
		initDeck();
		initImages();
		shuffleDeck();
		dealCards();
	}

	public void startTimer()
	{
		timeStart = System.currentTimeMillis();
	}

	public void initDeck()
	{
		unshuffledDeck.clear();
		for (int s = 1; s <= 4; s++) // goes through the suits
		{
			for (int r = 2; r <= 14; r++) // goes through ranks
			{
				unshuffledDeck.add(new Card(r, s));
			}
		}

		for (int i = 0; i < 52; i++)
		{
			shuffledDeck.add(unshuffledDeck.get(i));
		}
	}

	public void initImages()
	{
		int counter = 0;
		for (int s = 1; s <= 4; s++) // goes through the suits
		{
			for (int r = 2; r <= 14; r++) // goes through ranks
			{
				cardImages[r][s] = getImage(imgLocation, shuffledDeck.get(counter).getName());
				counter++;
			}
		}
	}

	public void shuffleDeck()
	{
		shuffledDeck.clear();

		for (int i = 0; i < 52; i++)
		{
			shuffledDeck.add(r.nextInt(shuffledDeck.size() + 1), unshuffledDeck.get(i));
		}
	}

	public void dealCards()
	{
		for (int z = 0; z < piles.length; z++)
			piles[z].clear();

		int counter = 0, counter2 = 51;
		for (int i = 0; i < piles.length; i++)
		{
			for (int i2 = 0; i2 <= counter; i2++)
			{
				System.out.println("Shuff Deck Size: " + shuffledDeck.size());
				if (counter2 <= 0)
					return;
				piles[i].add(shuffledDeck.remove(counter2));
				counter2--;
			}
			counter++;
		}
		for (int a = 0; a < piles.length; a++)
		{
			for (int o = 0; o < piles[a].size(); o++)
			{
				if (!piles[a].get(o).isFaceDown())
					piles[a].get(o).flip();
			}
		}
		flipLast();
		/*
		 * uncomment this when you are actually flipping to rear of cards
		 */
	}

	public void flipLast()
	{
		for (int i3 = 0; i3 < piles.length; i3++)
		{
			if (piles[i3].size() != 0)
			{
				if (piles[i3].get(piles[i3].size() - 1).isFaceDown())
					piles[i3].get(piles[i3].size() - 1).flip();
			}
		}
	}

	public void drawPiles()
	{
		int currentImgLocX = 0;
		int currentImgLocY = offset;

		for (int i = 0; i < piles.length; i++)
		{
			for (int i2 = 0; i2 < piles[i].size(); i2++)
			{
				if (piles[i].get(i2).isFaceDown())
					bg.drawImage(cardBack, currentImgLocX, currentImgLocY, cardWidth, cardHeight, this);
				else
				{
					bg.drawImage(cardImages[piles[i].get(i2).getRank()][piles[i].get(i2).getSuit()], currentImgLocX, currentImgLocY, cardWidth, cardHeight, this);
				}
				currentImgLocY += (cardHeight - cardYShift);
			}
			currentImgLocY = offset;
			currentImgLocX += cardWidth;
		}
	}

	public void drawHand()
	{
		if (hand.size() > 0)
		{
			for (int i = 0; i < hand.size(); i++)
			{
				if (!hand.get(i).isFaceDown())
					bg.drawImage(cardImages[hand.get(i).getRank()][hand.get(i).getSuit()], mouseX, mouseY + cardYShift / 2 * (i), cardWidth, cardHeight, this);
				else
					bg.drawImage(cardBack, mouseX, mouseY + cardYShift * 2 * (i + 1) - cardYShift * 2, cardWidth, cardHeight, this);
			}
		}
	}

	public void drawCompleted()
	{
		for (int i = 0; i < 4; i++)
		{
			if (completed[i].size() == 0)
				bg.drawRect(410 + (i * 100), 10, cardWidth, cardHeight);
			else
				bg.drawImage(cardImages[completed[i].get(completed[i].size() - 1).getRank()][completed[i].get(completed[i].size() - 1).getSuit()], 410 + (i * 100), 10, cardWidth, cardHeight, this);
		}
	}

	public void drawDrawn()
	{
		if (drawn.size() == 0)
			bg.drawRect(210, 10, cardWidth, cardHeight);
		else
			bg.drawImage(cardImages[drawn.get(drawn.size() - 1).getRank()][drawn.get(drawn.size() - 1).getSuit()], 210, 10, cardWidth, cardHeight, this);
	}

	public void clickedCompleted(int x, int y, int p, int c)
	{
		System.out.println("Called clikedCompleted");
		if (x >= 410 && x <= (410 + (4 * 100)))
		{
			z = (x - 410) / cardWidth;
			System.out.println("Clicked in complete region");
			if (hand.size() == 1)
			{
				if (completed[z].size() == 0)
				{
					System.out.println("Hand size: " + hand.size());
					System.out.println("Hand at 0 rank: " + hand.get(0).getRank());
					if (hand.get(0).getRank() == 14)
					{
						completed[z].add(hand.remove(0));
						System.out.println("Test");
					}
				} else
				{
					if (hand.get(0).getSuit() == completed[z].get(completed[z].size() - 1).getSuit())
					{
						int lastCardRank = completed[z].get(completed[z].size() - 1).getRank();
						System.out.println("Last Card Rank: " + lastCardRank);
						if (completed[z].get(completed[z].size() - 1).getRank() == 14)
						{
							if (hand.get(0).getRank() == 2)
								completed[z].add(hand.remove(0));
						} else if (hand.get(0).getRank() == (lastCardRank + 1))
							completed[z].add(hand.remove(0));
					}
				}
			}
		}
	}

	public void pickUp(int x, int y)
	{
		if (x >= 10 && x <= 10 + cardWidth && y >= 10 && y <= 10 + cardHeight)
		{
			if (shuffledDeck.size() != 0)
			{
				drawn.add(shuffledDeck.remove(shuffledDeck.size() - 1));
				drawn.get(drawn.size() - 1).flip();
			} else if (shuffledDeck.size() == 0)
			{
				while (drawn.size() != 0)
				{
					shuffledDeck.add(drawn.remove(drawn.size() - 1));
				}
			}
			return;
		}
		if (x > 1000 && x < 1000 + cardWidth) // Temp slot card
		{
			if (y > 200 && y < 200 + cardHeight)
			{
				if (hand.size() == 0)
				{
					if (tempSlot != null)
					{
						hand.add(tempSlot);
						tempSlot = null;
					}
				}
			}
		}
		if (x >= 210 && x <= 210 + cardWidth && y >= 10 && y <= 10 + cardHeight)
		// the drawn pile, clicking it puts it in hand
		{
			if (drawn.size() > 0)
				hand.add(drawn.remove(drawn.size() - 1));
			return;
		}

		if (x > (cardWidth * piles.length))
			return;
		if (y <= offset && x >= 400)
			return;

		p = x / cardWidth;
		c = (y - offset) / (cardHeight - cardYShift); // TODO Add if statement
														// to check if that
														// -cardYShift is
														// actually needed.

		while (piles[p].size() > c && !(y <= offset))
		{
			if (piles[p].get(c).isFaceDown())
				break;
			hand.add(piles[p].remove(c));
			System.out.println("In while loop, hand size is: " + hand.size());
		}

	}

	public void putDown(int x, int y)
	{
		p = x / cardWidth;
		c = (y - offset) / (cardHeight - cardYShift);

		System.out.println("Called putDown");
		if (x > 1000 && x < 1000 + cardWidth) // Temp slot card
		{
			if (y > 200 && y < 200 + cardHeight)
			{
				if (hand.size() == 1)
				{
					if (tempSlot == null)
					{
						tempSlot = hand.remove(hand.size() - 1);
					}
				}
			}
		}
		if (y >= offset && x > (cardWidth * piles.length))
		{
			System.out.println("Breaking out at putDown return 1");
			return;
		}
		if (y <= offset && x >= 400)
		{
			System.out.println("Breaking out at putDown return 2");
			clickedCompleted(x, y, p, c);
			return;
		}
		System.out.println("Got past breaks");
		// TODO Add the propper limitations for putting down
		if (piles[p].size() == 0)
		{
			piles[p].add(hand.remove(0));
		}
		if (!legalPutdown(p, c))
			return;
		while (hand.size() > 0)
		{
			piles[p].add(hand.remove(0));
		}
	}

	public boolean legalPutdown(int p, int c)
	{
		int pileEndRank = piles[p].get(piles[p].size() - 1).getRank();
		int pileEndSuit = piles[p].get(piles[p].size() - 1).getSuit();

		if (pileEndSuit == 2 || pileEndSuit == 3)
		{
			if (hand.get(0).getSuit() == 2 || hand.get(0).getSuit() == 3)
			{
				System.out.println("Broke out at suit check 1");
				return false;
			}
		}
		if (pileEndSuit == 1 || pileEndSuit == 4)
		{
			if (hand.get(0).getSuit() == 1 || hand.get(0).getSuit() == 4)
			{
				System.out.println("Broke out at suit check 2");
				return false;
			}
		}

		if (pileEndRank - 1 != hand.get(0).getRank())
		{
			System.out.println("Broke out at rank check");
			return false;
		}

		return true;
	}

	public void drawTempSlot()
	{
		if (tempSlot == null)
			bg.drawRect(1000, 200, cardWidth, cardHeight);
		else
			bg.drawImage(cardImages[tempSlot.getRank()][tempSlot.getSuit()], 1000, 200, cardWidth, cardHeight, this);
	}

	public void drawShuffledDeckRemaining()
	{
		if (shuffledDeck.size() > 0)
			bg.drawImage(cardBack, 10, 10, cardWidth, cardHeight, this);
		// bg.drawImage(cardImages[shuffledDeck.get(shuffledDeck.size() -
		// 1).getRank()][shuffledDeck.get(shuffledDeck.size() - 1).getSuit()],
		// 10, 10, cardWidth, cardHeight, this);
	}

	public void drawShuffledDeck()
	{
		int currentImgLocX = 0;
		int currentImgLocY = 0;
		int counter = 0;

		for (int s = 1; s <= 4; s++) // goes through the suits
		{
			for (int r = 2; r <= 14; r++) // goes through ranks
			{
				bg.drawImage(cardImages[shuffledDeck.get(counter).getRank()][shuffledDeck.get(counter).getSuit()], currentImgLocX, currentImgLocY, 150, 150, this);
				currentImgLocX += 135;
				counter++;
			}
			currentImgLocY += 200;
			currentImgLocX = 0;
		}
	}

	public boolean won()
	{
		for (int i = 0; i < completed.length; i++)
		{
			if (completed[i].size() != 14)
				return false;
		}
		return true;
	}

	public void drawStartScreen()
	{
		System.out.println("Drawing start screen");
		bg.setColor(Color.yellow);
		bg.fillRect(0, 0, 2000, 2000);
		bg.setColor(Color.blue);
		bg.drawString("Press spacebar to start Solitaire", 200, 500);
	}

	public void drawTimer()
	{
		bg.setColor(Color.red);
		long timer = (System.currentTimeMillis() - timeStart) / 1000;
		bg.drawString("Time taken seconds: " + timer, 100, 700);
		bg.setColor(Color.blue);
	}

	public void update(Graphics g)
	{
		paint(g);
	}

	public void paint(Graphics g)
	{
		random = new Color(r.nextInt(255), r.nextInt(255), r.nextInt(255));
		bg.clearRect(0, 0, 2000, 2000);
		bg.setFont(hugeFont);
		bg.setColor(Color.blue);
		if (!won())
			bg.drawImage(background, 0, 0, 1800, 900, this);
		else
		{
			bg.setColor(random);
			bg.fillRect(0, 0, 2000, 2000);
		}

		// offset = r.nextInt(500);

		if (startScreen)
			drawStartScreen();
		else
		{

			drawPiles();
			drawShuffledDeckRemaining();
			drawCompleted();
			drawDrawn();
			flipLast();
			drawTempSlot();
			drawHand();
			drawTimer();

			System.out.println("Comp 1: " + completed[0].size() + "Comp 2: " + completed[1].size() + "Comp 3: " + completed[2].size() + "Comp 4: " + completed[3].size());
			if (shuffleCounter <= 100)
			{
				shuffleDeck();
			}
			shuffleCounter++;
		}

		showStatus("X: " + mouseX + "   Y: " + mouseY);
		g.drawImage(offscreen, 0, 0, this);
		repaint();
	}

	public void mouseClicked(MouseEvent m)
	{
	}

	public void mouseEntered(MouseEvent arg0)
	{
	}

	public void mouseExited(MouseEvent arg0)
	{
	}

	public void mousePressed(MouseEvent m)
	{
		mouseX = m.getX();
		mouseY = m.getY();

		if (hand.size() == 0)
			pickUp(m.getX(), m.getY());
		else
			putDown(m.getX(), m.getY());
	}

	public void mouseReleased(MouseEvent arg0)
	{
	}

	public void keyPressed(KeyEvent k)
	{
		if (k.getKeyChar() == 'a')
			hand.clear();
		// if (k.getKeyChar() == 'z')
		// {
		// shuffleDeck();
		// dealCards();
		// }
		// if (k.getKeyChar() == 'q')
		// flipLast();
		if (k.getKeyChar() == ' ' && startScreen == true)
		{
			startScreen = false;
			ac.play();
			startTimer();
		}
		if (k.getKeyChar() == 'r')
		{
			restart();
		}
	}

	public void keyReleased(KeyEvent arg0)
	{
	}

	public void keyTyped(KeyEvent arg0)
	{
	}

	public void mouseDragged(MouseEvent arg0)
	{
	}

	public void mouseMoved(MouseEvent m)
	{
		mouseX = m.getX();
		mouseY = m.getY();
	}

}
