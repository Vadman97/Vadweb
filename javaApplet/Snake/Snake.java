import java.applet.AudioClip;
import java.awt.Color;
import java.awt.Dimension;
import java.awt.Font;
import java.awt.Graphics;
import java.awt.Image;
import java.awt.event.KeyEvent;
import java.awt.event.KeyListener;
import java.io.BufferedOutputStream;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.OutputStream;
import java.nio.charset.Charset;
import java.nio.charset.StandardCharsets;
import java.nio.file.OpenOption;
import java.nio.file.Path;
import java.nio.file.Paths;
import java.util.ArrayList;
import java.util.Random;
import java.util.Scanner;

import javax.swing.JApplet;

public class Snake extends JApplet implements Runnable, KeyListener
{
	private static final long	serialVersionUID	= 9093933096758032773L;
	// TODO Add apple streaks
	// TODO Add different high scores for different difficulties

	public Font					bigFont				= new Font("Calibri", 22, 22);
	public Font					hugeFont			= new Font("Calibri", 60, 50);
	public Font					massiveFont			= new Font("Calibri", 100, 100);
	int							width				= 1280, height = 720;
	int							widthOffset			= 20, heightOffset = 20;
	int							direction			= 1;
	int							counter				= 0;
	int							increment1, increment2;

	// 0 for north, 1 for east, 2 for south, 3 for west
	// declare an ArrayList of Body objects

	public ArrayList<Body>		snake				= new ArrayList<Body>();
	int							timeStep, maxSpeed;
	final int					squareSize			= 20;
	int							currentAppleCoordX	= 0;
	int							currentAppleCoordY	= 0;
	int							difficulty			= 0;
	public static Charset		ENCODING			= StandardCharsets.UTF_8;
	static int					highscore			= 0;
	boolean						moving				= true;
	boolean						appleAlive			= false;
	boolean						instructions		= true;
	boolean						choosingClear		= false;
	public ArrayList<Integer>	dirQueue			= new ArrayList<Integer>();
	Random						r					= new Random();
	Thread						t;
	Image						offscreen, impos;
	Dimension					dim;
	Graphics					bg;
	Color						rColor;
	public static AudioClip		ac;

	public void init()
	{
		t = new Thread(this);
		System.out.println("Initializing");
		resize(width, height);
		addKeyListener(this);
		setFocusable(true);
		dim = getSize();
		offscreen = createImage(dim.width, dim.height);
		bg = offscreen.getGraphics();
		rColor = new Color(r.nextInt(256), r.nextInt(256), r.nextInt(256));
		spawnApple();

		for (int i = 0; i < 1; i++)
		{
			snake.add(new Body(300 + 20 * i, 160));
		}
		bg.setFont(hugeFont);
		impos = getImage(getDocumentBase(), "impos.jpg");
		ac = getAudioClip(getDocumentBase(), "rick.wav");
		ac.play();
		t.start();
		System.out.println("Done Initializing");
		restart();
	}

	public void restart()
	{
		double t1, t2;
		t1 = System.currentTimeMillis();
		
		t.suspend();
		System.out.println("Restarting");
		snake.clear();
		dirQueue.clear();
		rColor = new Color(r.nextInt(256), r.nextInt(256), r.nextInt(256));
		spawnApple();

		for (int i = 0; i < 1; i++)
		{
			snake.add(new Body(300 + 20 * i, 160));
		}
		bg.setFont(hugeFont);
		direction = 1;
		timeStep = 150;
		maxSpeed = 40;
		moving = true;
		appleAlive = false;
		ac.stop();
		ac.play();
		instructions = true;
		System.out.println("Done Restarting");
		t2 = System.currentTimeMillis();
		t.resume();
		System.out.println("Restart millitime: " + (t2 - t1));
	}

	public void setupDiff()
	{
		if (difficulty == 0)
		{
			direction = 1;
			timeStep = 150;
			maxSpeed = 40;
			increment1 = 100;
			increment2 = 150;
		}
		if (difficulty == 1)
		{
			direction = 0;
			timeStep = 100;
			maxSpeed = 20;
			increment1 = 75;
			increment2 = 100;
		}
		if (difficulty == 2)
		{
			direction = 0;
			timeStep = 50;
			maxSpeed = 5;
			increment1 = 25;
			increment2 = 50;
		}
	}

	public void drawDeadText()
	{
		Color frontBack = new Color(0, 0, 255, 150);
		bg.setColor(Color.black);
		bg.fillRect(0, 0, width, height);
		bg.setColor(Color.white);
		bg.fillRect(0 + widthOffset, 0 + heightOffset, width - widthOffset * 2, height - heightOffset * 2);
		bg.setColor(frontBack);
		bg.fillRect(0 + widthOffset, 0 + heightOffset, width - widthOffset * 2, height - heightOffset * 2);
		bg.setColor(Color.ORANGE);
		bg.drawString("You lost! You got " + (snake.size() - 1) + " apples!", 100, 150);
		bg.drawString("Max highscore is: " + getHighScore(), 100, 400);
		bg.drawString("Press r to restart", 100, 300);
		bg.drawString("Press g to clear highscore", 100, 500);
		if (choosingClear)
			bg.drawString("Press h to confirm", 100, 600);
		if ((snake.size() - 1) > getHighScore())
		{
			write(Integer.toString((snake.size() - 1)), "hs.txt");
		}
	}

	public void paint(Graphics g)
	{
		rColor = new Color(r.nextInt(256), r.nextInt(256), r.nextInt(256));
		Color frontBack = new Color(0, 0, 255, 150);
		if (isDead())
		{
			drawDeadText();
		} else if (instructions)
		{
			bg.clearRect(0, 0, 2000, 2000);
			bg.drawString("Press 0 for easy, 1 for hard, 2 for imposiburu", 10, 225);
			bg.setColor(Color.ORANGE);
			bg.drawString("Welcome to Vads's Snake Game!", 0, 600);
			bg.drawString("Difficulty: " + difficulty, 0, 700);
			bg.drawString("Press spacebar to start", 0, 400);
			bg.drawString("Max highscore is: " + getHighScore(), 0, 150);
			bg.drawString("Press r to restart", 0, 300);
			bg.drawString("Press g to clear highscore", 0, 500);
			if (choosingClear)
				bg.drawString("Press h to confirm", 0, 600);
		} else
		{
			bg.setColor(Color.black);
			bg.fillRect(0, 0, width, height);
			bg.setColor(Color.white);
			bg.fillRect(0 + widthOffset, 0 + heightOffset, width - widthOffset * 2, height - heightOffset * 2);
			if (difficulty == 2)
			{
				bg.drawImage(impos, 0 + widthOffset, 0 + heightOffset, width - widthOffset * 2, height - heightOffset * 2, this);
			} else
			{
				bg.setColor(frontBack);
				bg.fillRect(0 + widthOffset, 0 + heightOffset, width - widthOffset * 2, height - heightOffset * 2);
			}
			drawApple();

			if (moving)
			{
				if (timeStep >= 50)
				{
					if (counter % increment1 == 0)
					{
						timeStep--;
						if (difficulty == 2)
							spawnApple();
					}
				} else
				{
					if (counter % increment2 == 0)
					{
						timeStep--;
						if (difficulty == 2)
							spawnApple();
					}
				}
				if (timeStep <= maxSpeed)
					timeStep = maxSpeed;
			}

			for (int i = 0; i < snake.size(); i++)
			{
				System.out.println("Drawing snake: " + i);
				if (timeStep > 100)
				{
					if (i % 2 == 0)
						bg.setColor(Color.green);
					else
						bg.setColor(Color.magenta);
				} else if (timeStep > 75)
				{
					if (i % 2 == 0)
						bg.setColor(Color.magenta);
					else
						bg.setColor(Color.orange);
				} else if (timeStep > 60)
				{
					if (i % 2 == 0)
						bg.setColor(Color.red);
					else
						bg.setColor(Color.white);
				} else if (timeStep > 0)
				{
					if (i % 2 == 0)
						bg.setColor(Color.red);
					else
						bg.setColor(Color.green);
				}
				bg.fill3DRect(snake.get(i).getPosX(), snake.get(i).getPosY(), squareSize, squareSize, true);
			}
		}
		showStatus("HS: " + getHighScore() + "   Queue Size: " + dirQueue.size() + "   TS: " + timeStep + "   Apple X: " + currentAppleCoordX + "   Apple Y: " + currentAppleCoordY + " Snake 0 X: " + snake.get(0).getPosX() + " Snake 0 Y: " + snake.get(0).getPosY());
		g.drawImage(offscreen, 0, 0, this);
		counter++;
		repaint();
	}

	public void drawApple()
	{
		if (timeStep > 100)
			bg.setColor(Color.red);
		else if (timeStep > 75)
			bg.setColor(Color.green);
		else if (timeStep > 40)
			bg.setColor(Color.cyan);
		if (appleAlive)
		{
			bg.fillRect(currentAppleCoordX, currentAppleCoordY, squareSize, squareSize);
		}
	}

	public int pixelXToBoard(int x)
	{
		return x / squareSize;
	}

	public int pixelYToBoard(int y)
	{
		return y / squareSize;
	}

	public void removeLast()
	{
		snake.remove(snake.size() - 1);
	}

	public void spawnApple()
	{
		currentAppleCoordX = pixelXToBoard(r.nextInt(width - widthOffset * 2) + widthOffset) * squareSize;
		currentAppleCoordY = pixelYToBoard(r.nextInt(height - heightOffset * 2) + heightOffset) * squareSize;
		appleAlive = true;
	}

	public boolean isCollidedApple()
	{
		if (snake.get(0).getPosX() == currentAppleCoordX)
			if (snake.get(0).getPosY() == currentAppleCoordY)
				return true;
		return false;
	}

	public void addToSnake()
	{
		if (direction == 0)
			snake.add(0, new Body(snake.get(0).getPosX(), snake.get(0).getPosY() - squareSize));
		else if (direction == 1)
			snake.add(0, new Body(snake.get(0).getPosX() + squareSize, snake.get(0).getPosY()));
		else if (direction == 2)
			snake.add(0, new Body(snake.get(0).getPosX(), snake.get(0).getPosY() + squareSize));
		else if (direction == 3)
			snake.add(0, new Body(snake.get(0).getPosX() - squareSize, snake.get(0).getPosY()));
	}

	public boolean isDead()
	{
		for (int i = 1; i < snake.size(); i++)
		{
			if (snake.get(i).positionX == snake.get(0).positionX)
			{
				if (snake.get(i).positionY == snake.get(0).positionY)
				{
					return true;
				}
			}
		}
		if (snake.get(0).getPosX() <= widthOffset / 2)
			return true;
		if (snake.get(0).getPosY() <= heightOffset / 2)
			return true;
		if (snake.get(0).getPosX() >= width - widthOffset)
			return true;
		if (snake.get(0).getPosY() >= height - heightOffset)
			return true;
		return false;
	}

	public void move()
	{
		if (direction == 0)
		{
			snake.add(0, new Body(snake.get(0).getPosX(), snake.get(0).getPosY() - squareSize));
		} else if (direction == 1)
		{
			snake.add(0, new Body(snake.get(0).getPosX() + squareSize, snake.get(0).getPosY()));
		} else if (direction == 2)
		{
			snake.add(0, new Body(snake.get(0).getPosX(), snake.get(0).getPosY() + squareSize));
		} else if (direction == 3)
		{
			snake.add(0, new Body(snake.get(0).getPosX() - squareSize, snake.get(0).getPosY()));
		}
		removeLast();
	}

	public void run()
	{
		try
		{
			while (true)
			{
				resize(width, height);

				if (!isDead())
				{
					if (!appleAlive)
						spawnApple();
					if (moving)
					{
						if (dirQueue.size() != 0)
						{
							direction = dirQueue.remove(0);
						}
						move();
					}
					if (isCollidedApple())
					{
						appleAlive = false;
						addToSnake();
					}
				}

				repaint();
				t.sleep(timeStep);
			}
		} catch (InterruptedException e)
		{

			System.out.println("Exception: " + e.getMessage());
		}
	}

	public int getHighScore()
	{
		try
		{
			readLargerTextFile("hs.txt");
		} catch (IOException e)
		{
			System.out.println(e.getMessage());
		}
		return highscore;
	}

	public void clearHighscore() throws IOException
	{
		String blank = "";
		Path path = Paths.get("C:\\Temp\\Snake\\", "hs.txt");
//		Path path = Paths.get("C:\\Users\\" + System.getProperty("user.name") + "\\Vadim Programs\\", "hs.txt");
		byte data[] = blank.getBytes();
		try (OutputStream out = new FileOutputStream(path.toString()); Scanner scanner = new Scanner(path, ENCODING.name()))
		{
			while (scanner.hasNextLine())
				out.write(data, 0, data.length);
		} catch (IOException x)
		{
			System.err.println(x);
		}
		drawDeadText();
	}

	public static void readLargerTextFile(String aFileName) throws IOException
	{
		Path path = Paths.get("C:\\Temp\\Snake\\", aFileName);
		try (Scanner scanner = new Scanner(path, ENCODING.name()))
		{
			while (scanner.hasNextLine())
			{
				highscore = Integer.parseInt(scanner.nextLine());
			}
		}
	}

	public void write(String hs, String aFileName)
	{
		Path path = Paths.get("C:\\Temp\\Snake\\", aFileName);

		byte data[] = hs.getBytes();
		try (OutputStream out = new FileOutputStream(path.toString()))
		{
			out.write(data, 0, data.length);
		} catch (IOException x)
		{
			System.err.println(x);
		}
	}

	public void update(Graphics g)
	{
		paint(g);
	}

	public void keyPressed(KeyEvent e)
	{
		char uS = e.getKeyChar();

		if (instructions)
		{
			if (uS == ' ')
			{
				instructions = false;
				setupDiff();
			}
			if (uS == '1')
				difficulty = 1;
			if (uS == '2')
				difficulty = 2;
			if (uS == '0')
				difficulty = 0;

		}

		if (uS == 'w' && direction != 2)
		{
			if (dirQueue.size() > 0)
			{
				if (dirQueue.get(dirQueue.size() - 1) != 0)
					dirQueue.add(0);
			} else
				dirQueue.add(0);
		}
		if (uS == 'd' && direction != 3)
		{
			if (dirQueue.size() > 0)
			{
				if (dirQueue.get(dirQueue.size() - 1) != 1)
					dirQueue.add(1);
			} else
				dirQueue.add(1);
		}
		if (uS == 's' && direction != 0)
		{
			if (dirQueue.size() > 0)
			{
				if (dirQueue.get(dirQueue.size() - 1) != 2)
					dirQueue.add(2);
			} else
				dirQueue.add(2);
		}
		if (uS == 'a' && direction != 1)
		{
			if (dirQueue.size() > 0)
			{
				if (dirQueue.get(dirQueue.size() - 1) != 3)
					dirQueue.add(3);
			} else
				dirQueue.add(3);
		}

		if (isDead())
			if (uS == 'r')
				restart();

		if (uS == 'g')
			choosingClear = true;

		if (choosingClear)
		{
			if (uS == 'h')
			{
				try
				{
					System.out.println("Clearing HS");
					clearHighscore();
				} catch (IOException e1)
				{
					System.out.println(e1.getMessage());
				}
				choosingClear = false;
			}
			if (uS != 'h' && uS != 'g')
				choosingClear = false;
		}

		if (uS == 'u')
			moving = false;
		if (uS == 'i')
			moving = true;
		if (uS == 'q')
			spawnApple();
		if (uS == 'b')
			addToSnake();
	}

	public void keyReleased(KeyEvent e)
	{
	}

	public void keyTyped(KeyEvent e)
	{
	}

}